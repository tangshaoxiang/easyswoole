<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/13
 * Time: 15:23
 */
namespace App\Lib\Upload;

use App\Lib\Utils;

class Base{

    public $fileType = "video";

    public $maxSize = 122;

    public $fileExtTypes = [
        'mp4',
        'x-flv'
    ];
    public $type = "";

    public function __construct($request)
    {
        $this->request = $request;
        $files = $this->request->getSwooleRequest()->files;
        $type = array_keys($files);
        $this->type = $type[0];
    }

    public function upload(){
        if ($this->type != $this->fileType) {
            return false;
        }

        $videos = $this->request->getUploadedFile($this->type);

        $this->size = $videos->getSize();

        $this->checkSize();

        $fileName = $videos->getClientFileName();

        $this->clientMediaType = $videos->getClientMediaType();

        var_dump($this->clientMediaType);
        $this->checkMediaType();

        $file = $this->getFile($fileName);
        var_dump($file);
        $flag = $videos->moveTo($file);
        var_dump($this->file);
        if (!empty($flag)) {
            return $this->file;
        }
        return false;
    }


    public function getFile($fileName) {
        $pathinfo = pathinfo($fileName);
        $extension = $pathinfo['extension'];
        $dirname = "/".$this->type."/".date("Y")."/".date("m");
        $dir = EASYSWOOLE_ROOT."/webroot".$dirname;
        if (!is_dir($dir)) {
            mkdir($dir,0777,true);
        }
        $basename = "/". Utils::getFileKey($fileName).".".$extension;
        $this->file = $dirname.$basename;
        return $dir.$basename;
    }


    public function checkMediaType(){
        $clientMediaType = explode("/",$this->clientMediaType);
        $clientMediaType = $clientMediaType[1]??"";
        if (empty($clientMediaType)) {
            throw new \Exception("上传{$this->type}文件不合法");
        }
        if (!in_array($clientMediaType,$this->fileExtTypes)) {
            throw new \Exception("上传{$this->type}文件不合法");
        }
        return true;
    }


    public function checkSize() {
        if (empty($this->size)) {
            return false;
        }
    }
}