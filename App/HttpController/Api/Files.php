<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;

use App\HttpController\Api\Base;

class Files extends Base
{

    public function test(){
      return $this->writeJson("200","success","成功");
    }
}