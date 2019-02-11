<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;


class Index extends Base
{
    public function video(){

        new abc();
        $data = [
            'id' => 1,
            'name' => 'darian',
            'param' => $this->request()->getRequestParam()
        ];
        return $this->writeJson('200',"成功",$data);
    }
}