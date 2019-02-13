<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/13
 * Time: 15:23
 */
namespace App\Lib\Upload;

class Base{

    public $type = "";

    public function __construct($request)
    {
        $this->request = $request;
        $files = $this->request->getSwooleRequest()->files;
        $type = array_keys($files);
        $this->type = $type[0];
        var_dump($this->type);
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

        $this->checkMediaType();
        print_r($fileName);
        print_r($this->clientMediaType);

        $this->getFile($fileName);
    }


    public function getFile($fileName) {
        $pathinfo = pathinfo($fileName);
        print_r($pathinfo);
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