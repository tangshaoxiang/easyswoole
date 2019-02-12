<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2018/10/18 0018
 * Time: 9:43
 */
namespace App\Lib\Process;

use EasySwoole\Component\Di;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Trace\Logger;
use Swoole\Process;

class Consumer extends AbstractProcess
{
    private $isRun = false;
    public function run($arg)
//    public function run($arg)
    {
        // TODO: Implement run() method.
        /*
         * 举例，消费redis中的队列数据
         * 定时500ms检测有没有任务，有的话就while死循环执行
         */
        $this->addTick(500,function (){
            if(!$this->isRun){
                var_dump(111);
                $this->isRun = true;
//                $redis = new \redis();//此处为伪代码，请自己建立连接或者维护redis连接
                while (true){
                    var_dump(222);
                    try{
                        var_dump(333);
                        $task = Di::getInstance()->set("REDIS")->lPop('imooc_list_test');
//                        $task = $redis->lPop('task_list');
                        var_dump($this->getProcessName()."---".$task);

                        if($task){
                            var_dump($this->getProcessName()."---".$task);
                            // do you task
                            //发送邮件，推送消息，等待，写log
                            Logger::getInstance()->log($this->getProcessName()."---".$task);
                        }else{
                            break;
                        }
                    }catch (\Throwable $throwable){
                        var_dump(444);
                        break;
                    }
                }
                $this->isRun = false;
            }
//            var_dump($this->getProcessName().' task run check');
        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
    }
}