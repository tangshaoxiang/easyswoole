<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;

use App\HttpController\Api\Base;
use App\Lib\Upload\Image;
use App\Lib\Upload\Video;

class Files extends Base
{

    public function file(){
      $request  = $this->request();
      $videos  = $request->getUploadedFile("files");
      $flag = $videos->moveTo("/home/wwwroot/www.darian.xin/easyswoole/webroot/1.mp4");
      $data = [
          'url'=>"1.mp4",
          'flag'=>$flag
      ];
      if ($flag){
          return $this->writeJson("200","success",$flag);
      }else{
          return $this->writeJson("400","error",$flag);
      }
    }

    public function fileTwo(){
        $request  = $this->request();
        try {
//            $obj = new Video($request);
            $obj = new Image($request);
            $file = $obj->upload();
        }catch (\Exception $e){
            return $this->writeJson(400,$e->getMessage(),[]);
        }

        if (empty($file)){
              return $this->writeJson(400,"上传失败",[]);
        }
        $data = [
          "url" =>$file
        ];
        return $this->writeJson(200,"ok",$data);

    }
}