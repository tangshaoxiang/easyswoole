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

    public function _initialize(){
        // 实例云信的库  去官网注册会生成key和secret
        $yunxinConfig = Config::getInstance()->getConf("yunxin");
        $this->AppKey = $yunxinConfig['AppKey']; //你的Appkey
        $this->AppSecret = $yunxinConfig['AppSecret']; //你的AppSecret
        $this->yxsdk = new YunxinServer($this->AppKey,$this->AppSecret,'curl');
        $this->codeMsg = Config::getInstance()->getConf("yunxinCode");  //这是code状态表
        var_dump($yunxinConfig);
        var_dump($this->codeMsg);
    }

    public function test(){
        var_dump("yunxin测试");
    }

}