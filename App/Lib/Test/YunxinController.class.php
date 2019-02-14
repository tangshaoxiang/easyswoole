<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/1
 * Time: 9:51
 */
namespace Api\Controller;
use Think\Controller;

class YunxinController extends Controller{

    private $model;
    private $AppKey = '';     // key
    private $AppSecret = '';  // secret

    public function _initialize(){
        // 实例云信的库
        $this->model = new \Org\Util\YunxinServer($this->AppKey,$this->AppSecret,'curl');
    }

    /**
     * 创建云信ID
     * 
    */
    public function createUserId($userid)
    {
        $data = M('user')->field('id accid,username,nickname name,headimgurl icon')->where('is_del = 0 AND id='.$userid)->find();
        $token = md5($data['accid'].'abc');
        // 写入到云信服务器
        $accid = 'abc'.$data['accid'];
        $name = $data['name'];
        $icon = $data['icon'];
        $info = $this->model->createUserIds($accid,$name,'{}',$icon,$token);
        return $info;
    }

    public function updateUinfos($accid,$name,$icon,$sign,$email,$birth,$mobile,$gender,$ex){
        $info = $this->model->updateUinfo($accid,$name,$icon,$sign,$email,$birth,$mobile,$gender,$ex);
        return $info;
    }

    // 获取指定用户的云信ID
    public function getUinfos(){
        $accid = I('request.id');
        $accid = array($accid);
        $info = $this->model->getUinfoss($accid);
        print_r($info);
        return $info;
    }

    // 创建聊天室
    /**
     * @param $accid 聊天室的ID
     * @param $name  聊天室的名称
     */
    public function chatroomCreate($accid,$name){
        $info = $this->model->chatroomCreates($accid,$name);
        return $info;
    }

    // 查询聊天室
    public function chatroomget(){
        $roomid = I('request.id');
        $info = $this->model->chatroomgets($roomid);
        print_r($info);
        return $info;
    }

    /**
     * 更新聊天室
     * @param $roomid  聊天室ID
     * @param $name    聊天室名称
     * @return array
     */
    public function chatroomUpdate($roomid,$name){
        $info = $this->model->chatroomUpdates($roomid,$name);
        return $info;
    }

    /**
     * 修改聊天室开启或关闭聊天室
     * @param $roomid        聊天室ID
     * @param $operator      创建者ID
     * @param string $status 修改还是关闭  false => 修改
     */
    public function chatroomToggleClose($roomid,$operator){
        $info = $this->model->chatroomToggleCloses($roomid,$operator);
        return $info;
    }

    public function chatroomToggleStat($roomid,$operator){
        $info = $this->model->chatroomToggleStats($roomid,$operator);
        return $info;
    }

    /**
     *设置聊天室内用户角色
     * @param $roomid            // 聊天室ID
     * @param $operator          // 操作者账号accid   operator必须是创建者
     * @param $target            // 被操作者账号accid
     * @param $opt
     *      1: 设置为管理员，operator必须是创建者
            2:设置普通等级用户，operator必须是创建者或管理员
            -1:设为黑名单用户，operator必须是创建者或管理员
            -2:设为禁言用户，operator必须是创建者或管理员
     * @param string $optvalue   // true:设置；false:取消设置
     */
    public function chatroomSetMemberRole($roomid,$operator,$target,$opt,$optvalue){
        $info = $this->model->chatroomSetMemberRoles($roomid,$operator,$target,$opt,$optvalue);
        return $info;
    }

    /**
     * 云信消息抄送接口
     */
    public function receiveMsg()
    {
        $body = @file_get_contents('php://input');
        $data = json_decode($body,true);
        //file_put_contents('/data/server/work_justeasy_cn/debug.txt',$body);
        if($data){
            $d['eventType']       = $data['eventType'];
            $d['attach']          = $data['attach'];
            $d['ext']             = $data['ext'];
            $d['fromAccount']     = $data['fromAccount'];
            $d['fromAvator']      = $data['fromAvator'];
            $d['fromClientType']  = $data['fromClientType'];
            $d['fromExt']         = $data['fromExt'];
            $d['fromNick']        = $data['fromNick'];
            $d['msgTimestamp']    = $data['msgTimestamp'];
            $d['msgType']         = $data['msgType'];
            $d['msgidClient']     = $data['msgidClient'];
            $d['resendFlag']      = $data['resendFlag'];
            $d['roleInfoTimetag'] = $data['roleInfoTimetag'];
            $d['roomId']          = $data['roomId'];
            $d['antispam']        = $data['antispam'];
            $info = M('receivemsg')->add($d);
            if($info){
                echo 200;
            }
        }else{
            echo 500;
        }
    }


}