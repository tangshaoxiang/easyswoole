<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Lib\Redis\Redis;
use App\Lib\Process\Consumer;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Di;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {

        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        self::loadConf();
        PoolManager::getInstance()->register(MysqlPool::class, Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
    }

    /**
     * 加载配置文件
     */
    public static function loadConf()
    {
        $files = File::scanDirectory(EASYSWOOLE_ROOT . '/App/Conf');
        if (is_array($files)) {
            foreach ($files['files'] as $file) {
                $fileNameArr = explode('.', $file);
                $fileSuffix = end($fileNameArr);
                if ($fileSuffix == 'php') {
                    Config::getInstance()->loadFile($file);//引入之后,文件名自动转为小写,成为配置的key
                }
            }
        }
    }

    public static function mainServerCreate(EventRegister $register)
    {
//        Di::getInstance()->set("MYSQL",\MysqliDb::class,Array(
//            'host'          => '127.0.0.1',
//            'port'          => '3306',
//            'user'          => 'root',
//            'timeout'       => '5',
//            'charset'       => 'utf8mb4',
//            'password'      => 'root',
//            'database'      => 'swoole',
//            'POOL_MAX_NUM'  => '20',
//            'POOL_TIME_OUT' => '0.1',
//        ));
        Di::getInstance()->set("REDIS",Redis::getInstance());

        // TODO: Implement mainServerCreate() method.
        $allNum = 3;
        for ($i = 0 ;$i < $allNum;$i++){
            ServerManager::getInstance()->getSwooleServer()->addProcess((new Consumer("consumer_{$i}"))->getProcess());
        }


//        $register->add($register::onWorkerStart, function (\swoole_server $server, int $workerId) {
//            if ($server->taskworker == false) {
//                PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(1);
//                //PoolManager::getInstance()->getPool(RedisPool::class)->preLoad(预创建数量,必须小于连接池最大数量);
//            }
//
//            // var_dump('worker:' . $workerId . 'start');
//        });
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}