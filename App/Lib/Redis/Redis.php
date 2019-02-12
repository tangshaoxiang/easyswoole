<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/12
 * Time: 10:03
 */
namespace App\Lib\Redis;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;

class Redis {
    use Singleton;

    public $redis = "";

    private function __construct()
    {
        if(!extension_loaded('redis')){
            throw new \Exception("redis.so文件不存在");
        }

        try {
            $redisConfig = Config::getInstance()->getConf("REDIS");
            $this->redis = new \Redis();
            $result = $this->redis->connect($redisConfig['host'],$redisConfig['port'],$redisConfig['POOL_TIME_OUT']);
        } catch (\Exception $e) {
//            throw new \Exception($e->getMessage());
            throw new \Exception("redis服务异常");
        }

        if ($result === false){
            throw new \Exception("redis 链接失败");
        }
    }

    public function get($key){
        if (empty($key)) {
            return "";
        }
       return $this->redis->get($key);
    }

    /**
     * 从队列的左边出队一个元素
     * @param $key
     * @return string
     */
    public function lPop($key){
        if (empty($key)) {
            return "";
        }
        return $this->redis->lPop($key);
    }

    /**
     * 从队列的右边入队一个元素或多个元素
     * @param $key
     * @param $val
     * @return int|string
     */
    public function rPush($key,$val){
        if (empty($key)) {
            return "";
        }
        return $this->redis->rPush($key,$val);

    }
}