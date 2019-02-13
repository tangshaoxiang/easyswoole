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
        print_r($files);
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

        print_r($fileName);
        print_r($this->clientMediaType);
    }

    public function checkSize() {
        if (empty($this->size)) {
            return false;
        }
    }
}