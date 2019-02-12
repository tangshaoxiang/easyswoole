<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;

use App\Lib\Redis\Redis;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\Message\Status;


class Index extends Base
{
    public function video(){
        try {
            MysqlPool::invoke(function (MysqlObject $mysqlObject) {
                $table_name = 'test';
                $data = $mysqlObject ->get($table_name);
                $sql = $mysqlObject->getLastQuery();
                $res = [
                    'id' => 1,
                    'name' => 'darian',
                    'param' => $this->request()->getRequestParam(),
                    'data' => $data

                ];
                return $this->writeJson('200',"成功",$res);
            });
        } catch (\Throwable $throwable) {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, $throwable->getMessage());
        }catch (PoolEmpty $poolEmpty){
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '没有链接可用');

        }catch (PoolUnRegister $poolUnRegister){
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '连接池未注册');
        }
    }


    public function getRedis(){
//        $redis = new \Redis();
//        $redis->connect("127.0.0.1",6379,5);
//        $redis->set("singwa456",90);
//        return $this->writeJson("200","success",$redis->get("singwa456"));


        $mysqlConfig = Config::getInstance()->getConf("mysql");
        $this->writeJson("200","成功",$mysqlConfig);

        $redisConfig = Config::getInstance()->getConf("redis");
        $this->writeJson("200","成功",$redisConfig);

        $redisConfig = Config::getInstance()->getConf("REDIS");
        $this->writeJson("200","成功",$redisConfig);

       $singwa = Redis::getInstance()->get("singwa456");
       $this->writeJson("200","success",$singwa);


//        PoolManager::getInstance()->register(RedisPool::class, Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));
//        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj(Config::getInstance()->getConf('REDIS. '));
//        $redis->set('name', 'blank');
//        $singwa = $redis->get('name');
//        $name = $redis->get('singwa456');
//        var_dump($name);
//        var_dump($singwa);
//        /*
//         * string(5) "blank"
//         */
//        PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($redis);
    }

    public function yaconf(){
        $res = \Yaconf::get('redis');
      return  $this->writeJson("200","yes",$res);
    }
}