<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;

use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
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
        $redis = new \Redis();
        $redis->connect("127.0.0.1",6379,5);
        $redis->set("singwa456",90);
        return $this->writeJson("200","success",$redis->get("singwa456"));
    }
}