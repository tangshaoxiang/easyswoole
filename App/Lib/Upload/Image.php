<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/13
 * Time: 15:22
 */
namespace App\Lib\Upload;



class Image extends Base {

    /**
     * 文件类型
     * @var string
     */
    public $fileType = "image";

    public $maxSize = 122;

    public $fileExtTypes = [
        'jpeg',
        'png',
    ];

}