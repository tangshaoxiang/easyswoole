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


class Index extends Base
{
    public function video(){

        try {
            MysqlPool::invoke(function (MysqlObject $mysqlObject) {
                $model = new UserModel($mysqlObject);
                $model->insert(new UserBean($this->request()->getRequestParam()));
            });
        } catch (\Throwable $throwable) {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, $throwable->getMessage());
        }catch (PoolEmpty $poolEmpty){
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '没有链接可用');

        }catch (PoolUnRegister $poolUnRegister){
            $this->writeJson(Status::CODE_BAD_REQUEST, null, '连接池未注册');
        }
        $res = [
            'id' => 1,
            'name' => 'darian',
            'param' => $this->request()->getRequestParam(),

        ];
        return $this->writeJson('200',"成功",$res);
    }
}