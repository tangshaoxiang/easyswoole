<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;



class UploadFileSystem extends Base
{

    public function fileSystem(){
       $this->writeJson("200","sucdes","成功");
    }
}