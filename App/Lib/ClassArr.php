<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/13
 * Time: 18:03
 */

namespace App\Lib;

/**
 * 做一些反射机制有关的处理
 * Class ClassArr
 * @package App\Lib
 */
class ClassArr {

    public function uploadClassStat() {
        return [
            "image" => "\App\Lib\Upload\Image",
            "video" => "\App\Lib\Upload\Video",
        ];
    }

    public function initClass($type,$supportedClass,$params = [],$needInstance = true){
            if (!array_key_exists($type,$supportedClass)){
                return false;
            }
            $className = $supportedClass[$type];
            return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params[0],$params[1]):$className;
    }
}