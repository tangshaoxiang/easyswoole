<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/13
 * Time: 15:22
 */
namespace App\Lib\Upload;



class Video extends Base {

    /**
     * 文件类型
     * @var string
     */
    public $fileType = "videos";

    public $maxSize = 122;

    public $fileExtTypes = [
        'mp4',
        'x-flv'
    ];

}