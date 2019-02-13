<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/13
 * Time: 17:08
 */
namespace App\Lib;

class Utils {

    /**
     * 生成唯一的key
     * @param $str
     * @return bool|string
     */
    public static function getFileKey($str){
        return substr(md5(self::makeRandomString().$str.time().rand(0,9999)),8,16);
    }

    /**
     * 生成随机字符串
     * @param int $length 长度
     * @return null|string 生成的随机字符串
     */
    public static function makeRandomString($length = 1){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) -1;
        for ($i =0;$i<$length;$i++){
            $str.= $strPol[rand(0,$max)];
            //rand($min,$max);生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}