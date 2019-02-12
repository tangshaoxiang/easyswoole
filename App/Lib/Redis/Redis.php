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
}