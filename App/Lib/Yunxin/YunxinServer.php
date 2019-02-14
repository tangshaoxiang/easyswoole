<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/14
 * Time: 15:31
 */
namespace App\Lib\Yunxin;
use EasySwoole\Component\Singleton;

class YunxinServer {
    use Singleton;

    private $AppKey;                //开发者平台分配的AppKey
    private $AppSecret;             //开发者平台分配的AppSecret,可刷新
    private $Nonce;					//随机数（最大长度128个字符）
    private $CurTime;             	//当前UTC时间戳，从1970年1月1日0点0 分0 秒开始到现在的秒数(String)
    private $CheckSum;				//SHA1(AppSecret + Nonce + CurTime),三个参数拼接的字符串，进行SHA1哈希计算，转化成16进制字符(String，小写)
    const   HEX_DIGITS = "0123456789abcdef";

    /**
     * 参数初始化
     * @param $AppKey
     * @param $AppSecret
     * @param $RequestType [选择php请求方式，fsockopen或curl,若为curl方式，请检查php配置是否开启]
     */
    public function __construct($AppKey, $AppSecret, $RequestType='curl'){
        $this->AppKey = $AppKey;
        $this->AppSecret = $AppSecret;
        $this->RequestType = $RequestType;
    }


    /**
     * API checksum校验生成
     * @param  void
     * @return $CheckSum(对象私有属性)
     */
    public function checkSumBuilder(){
        //此部分生成随机字符串
        $hex_digits = self::HEX_DIGITS;
        $this->Nonce;
        for($i=0;$i<128;$i++){			//随机字符串最大128个字符，也可以小于该数
            $this->Nonce.= $hex_digits[rand(0,15)];
        }
        $this->CurTime = (string)(time());	//当前时间戳，以秒为单位

        $join_string = $this->AppSecret.$this->Nonce.$this->CurTime;
        $this->CheckSum = sha1($join_string);
        //print_r($this->CheckSum);
    }


    /**
     * 将json字符串转化成php数组
     * @param  $json_str
     * @return $json_arr
     */
    public function json_to_array($json_str){
        if(is_array($json_str) || is_object($json_str)){
            $json_str = $json_str;
        }else if(is_null(json_decode($json_str))){
            $json_str = $json_str;
        }else{
            $json_str =  strval($json_str);
            $json_str = json_decode($json_str,true);
        }
        $json_arr=array();
        foreach($json_str as $k=>$w){
            if(is_object($w)){
                $json_arr[$k]= $this->json_to_array($w); //判断类型是不是object
            }else if(is_array($w)){
                $json_arr[$k]= $this->json_to_array($w);
            }else{
                $json_arr[$k]= $w;
            }
        }
        return $json_arr;
    }


    /**
     * 使用CURL方式发送post请求
     * @param  $url     [请求地址]
     * @param  $data    [array格式数据]
     * @return $请求返回结果(array)
     */
    public function postDataCurl($url,$data){
        $this->checkSumBuilder();       //发送请求前需先生成checkSum

        $timeout = 5000;
        $http_header = array(
            'AppKey:'.$this->AppKey,
            'Nonce:'.$this->Nonce,
            'CurTime:'.$this->CurTime,
            'CheckSum:'.$this->CheckSum,
            'Content-Type:application/x-www-form-urlencoded;charset=utf-8'
        );

        $postdataArray = array();
        foreach ($data as $key=>$value){
            array_push($postdataArray, $key.'='.urlencode($value));
        }
        $postdata = join('&', $postdataArray);

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt ($ch, CURLOPT_HEADER, false );
        curl_setopt ($ch, CURLOPT_HTTPHEADER,$http_header);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (false === $result) {
            $result =  curl_errno($ch);
        }
        curl_close($ch);

        return $this->json_to_array($result) ;
    }


    /**
     * 使用FSOCKOPEN方式发送post请求
     * @param  $url     [请求地址]
     * @param  $data    [array格式数据]
     * @return $请求返回结果(array)
     */
    public function postDataFsockopen($url,$data){
        $this->checkSumBuilder();       //发送请求前需先生成checkSum

        // $postdata = '';
        $postdataArray = array();
        foreach ($data as $key=>$value){
            array_push($postdataArray, $key.'='.urlencode($value));
            // $postdata.= ($key.'='.urlencode($value).'&');
        }
        $postdata = join('&', $postdataArray);
        // building POST-request:
        $URL_Info=parse_url($url);
        if(!isset($URL_Info["port"])){
            $URL_Info["port"]=80;
        }
        $request = '';
        $request.="POST ".$URL_Info["path"]." HTTP/1.1\r\n";
        $request.="Host:".$URL_Info["host"]."\r\n";
        $request.="Content-type: application/x-www-form-urlencoded;charset=utf-8\r\n";
        $request.="Content-length: ".strlen($postdata)."\r\n";
        $request.="Connection: close\r\n";
        $request.="AppKey: ".$this->AppKey."\r\n";
        $request.="Nonce: ".$this->Nonce."\r\n";
        $request.="CurTime: ".$this->CurTime."\r\n";
        $request.="CheckSum: ".$this->CheckSum."\r\n";
        $request.="\r\n";
        $request.=$postdata."\r\n";

        // print_r($request);
        $fp = fsockopen($URL_Info["host"],$URL_Info["port"]);
        fputs($fp, $request);
        $result = '';
        while(!feof($fp)) {
            $result .= fgets($fp, 128);
        }
        fclose($fp);

        $str_s = strpos($result,'{');
        $str_e = strrpos($result,'}');
        $str = substr($result, $str_s,$str_e-$str_s+1);
        return $this->json_to_array($str);
    }


    /**
     * 使用CURL方式发送post请求（JSON类型）
     * @param  $url 	[请求地址]
     * @param  $data    [array格式数据]
     * @return $请求返回结果(array)
     */
    public function postJsonDataCurl($url,$data){
        $this->checkSumBuilder();		//发送请求前需先生成checkSum

        $timeout = 5000;
        $http_header = array(
            'AppKey:'.$this->AppKey,
            'Nonce:'.$this->Nonce,
            'CurTime:'.$this->CurTime,
            'CheckSum:'.$this->CheckSum,
            'Content-Type:application/json;charset=utf-8'
        );

        $postdata = json_encode($data);

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt ($ch, CURLOPT_HEADER, false );
        curl_setopt ($ch, CURLOPT_HTTPHEADER,$http_header);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (false === $result) {
            $result =  curl_errno($ch);
        }
        curl_close($ch);
        return $this->json_to_array($result) ;
    }


    /**
     * 使用FSOCKOPEN方式发送post请求（json）
     * @param  $url     [请求地址]
     * @param  $data    [array格式数据]
     * @return $请求返回结果(array)
     */
    public function postJsonDataFsockopen($url, $data){
        $this->checkSumBuilder();       //发送请求前需先生成checkSum

        $postdata = json_encode($data);

        // building POST-request:
        $URL_Info=parse_url($url);
        if(!isset($URL_Info["port"])){
            $URL_Info["port"]=80;
        }
        $request = '';
        $request.="POST ".$URL_Info["path"]." HTTP/1.1\r\n";
        $request.="Host:".$URL_Info["host"]."\r\n";
        $request.="Content-type: application/json;charset=utf-8\r\n";
        $request.="Content-length: ".strlen($postdata)."\r\n";
        $request.="Connection: close\r\n";
        $request.="AppKey: ".$this->AppKey."\r\n";
        $request.="Nonce: ".$this->Nonce."\r\n";
        $request.="CurTime: ".$this->CurTime."\r\n";
        $request.="CheckSum: ".$this->CheckSum."\r\n";
        $request.="\r\n";
        $request.=$postdata."\r\n";

        print_r($request);
        $fp = fsockopen($URL_Info["host"],$URL_Info["port"]);
        fputs($fp, $request);
        $result = '';
        while(!feof($fp)) {
            $result .= fgets($fp, 128);
        }
        fclose($fp);

        $str_s = strpos($result,'{');
        $str_e = strrpos($result,'}');
        $str = substr($result, $str_s,$str_e-$str_s+1);
        return $this->json_to_array($str);
    }


    /**
     * 创建云信ID
     * 1.第三方帐号导入到云信平台；
     * 2.注意accid，name长度以及考虑管理秘钥token
     * @param  $accid     [云信ID，最大长度32字节，必须保证一个APP内唯一（只允许字母、数字、半角下划线_、@、半角点以及半角-组成，不区分大小写，会统一小写处理）]
     * @param  $name      [云信ID昵称，最大长度64字节，用来PUSH推送时显示的昵称]
     * @param  $props     [json属性，第三方可选填，最大长度1024字节]
     * @param  $icon      [云信ID头像URL，第三方可选填，最大长度1024]
     * @param  $token     [云信ID可以指定登录token值，最大长度128字节，并更新，如果未指定，会自动生成token，并在创建成功后返回]
     * @return $result    [返回array数组对象]
     */
    public function userRegistrId($accid,$name='',$props='{}',$icon='',$token=''){
        $url = 'https://api.netease.im/nimserver/user/create.action';
        $data= array(
            'accid' => $accid,
            'name'  => $name,
            'props' => $props,
            'icon'  => $icon,
            'token' => $token
        );
        if($this->RequestType=='curl'){
            $result = $this->postDataCurl($url,$data);
        }else{
            $result = $this->postDataFsockopen($url,$data);
        }
        return $result;
    }


    /**
     * 获取用户名片
     * @param $accid
     * @return array
     */
    public function getUserInfo($accid){
        $url = 'https://api.netease.im/nimserver/user/getUinfos.action';
        $data = array(
            'accids' => json_encode($accid)
        );
        if($this->RequestType=='curl'){
            $result = $this->postDataCurl($url,$data);
        }else{
            $result = $this->postDataFsockopen($url,$data);
        }
        return $result;
    }

    /**
     * 更新用户名片
     * @param $accid
     * @return array
     */
    public function updateMyInfo($accid,$name='',$icon='',$sign='',$email='',$birth='',$mobile='',$gender=0){
        $url = 'https://api.netease.im/nimserver/user/updateUinfo.action';
        $data = array(
            'accid'=>$accid,
            'name'=>$name,
            'icon'=>$icon,
            'sign'=>$sign,
            'email'=>$email,
            'birth'=>$birth,
            'mobile'=>$mobile,
            'gender'=>$gender
        );

        if($this->RequestType=='curl'){
            $result = $this->postDataCurl($url,$data);
        }else{
            $result = $this->postDataFsockopen($url,$data);
        }
        return $result;
    }

    /**
     * 消息功能-发送普通消息
     * @param  $from       [发送者accid，用户帐号，最大32字节，APP内唯一]
     * @param  $ope        [0：点对点个人消息，1：群消息，其他返回414]
     * @param  $to        [ope==0是表示accid，ope==1表示tid]
     * @param  $type        [0 表示文本消息,1 表示图片，2 表示语音，3 表示视频，4 表示地理位置信息，6 表示文件，100 自定义消息类型]
     * @param  $body       [请参考下方消息示例说明中对应消息的body字段。最大长度5000字节，为一个json字段。]
     * @param  $option       [发消息时特殊指定的行为选项,Json格式，可用于指定消息的漫游，存云端历史，发送方多端同步，推送，消息抄送等特殊行为;option中字段不填时表示默认值]
     * @param  $pushcontent      [推送内容，发送消息（文本消息除外，type=0），option选项中允许推送（push=true），此字段可以指定推送内容。 最长200字节]
     * @return $result      [返回array数组对象]
     */
    public function sendMsg($from,$ope,$to,$type,$body,$option=array("push"=>false,"roam"=>true,"history"=>false,"sendersync"=>true, "route"=>false),$pushcontent=''){
        $url = 'https://api.netease.im/nimserver/msg/sendMsg.action';
        $data= array(
            'from' => $from,
            'ope' => $ope,
            'to' => $to,
            'type' => $type,
            'body' => json_encode($body),
            'option' => json_encode($option),
            'pushcontent' => $pushcontent
        );
        if($this->RequestType=='curl'){
            $result = $this->postDataCurl($url,$data);
        }else{
            $result = $this->postDataFsockopen($url,$data);
        }
        return $result;
    }

}