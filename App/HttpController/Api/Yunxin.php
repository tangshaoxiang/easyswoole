<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/14
 * Time: 15:34
 */
namespace App\HttpController\Api;

use App\Lib\Yunxin\YunxinServer;
use EasySwoole\EasySwoole\Config;

class Yunxin extends Base {
    private $yxsdk;
    private $AppKey;
    private $AppSecret;
    private $codeMsg;

    public function __construct(){
        parent::__construct();
        // 实例云信的库  去官网注册会生成key和secret
        $yunxinConfig = Config::getInstance()->getConf("yunxin");
        $this->AppKey = $yunxinConfig['AppKey']; //你的Appkey
        $this->AppSecret = $yunxinConfig['AppSecret']; //你的AppSecret
        $this->yxsdk = YunxinServer::getInstance($this->AppKey,$this->AppSecret,'curl');
        $this->codeMsg = Config::getInstance()->getConf("yun_xin_code");  //这是code状态表
    }

    /**
     * 注册云信
     */
    public function userRegistrId(){
        var_dump("注册云信ID");
//        $result = $this->yxsdk->userRegistrId("666666","darian","{}","https://api.tangyijiqiren.com/images/daBai.png","tsx521");
        $result = $this->yxsdk->userRegistrId("88888888","darian","{}","https://api.tangyijiqiren.com/images/daBai.png","tsx521");
        var_dump($result);
    }

    public function sendMsg(){
        $result = $this->yxsdk->sendMsg("666666","0","88888888");
    }



}