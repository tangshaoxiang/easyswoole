<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;


use EasySwoole\Mysqli\Mysqli;

class Index extends Base
{
    public function video(){

        $conf = new \EasySwoole\Mysqli\Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL'));
        return $conf;
        $db = new Mysqli($conf);
        $data = $db->get('test');//获取一个表的数据
        $res = [
            'id' => 1,
            'name' => 'darian',
            'param' => $this->request()->getRequestParam(),
            'data' => $data
        ];
        return $this->writeJson('200',"成功",$res);
    }
}