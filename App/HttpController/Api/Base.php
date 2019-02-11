<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class Base extends Controller
{
    public function index()
    {
    }


    /**
     * 权限相关
     * @param null|string $action
     * @return bool|null
     */
    public function onRequest(?string $action): ?bool
    {
        return true;
    }

    /**
     * @param \Throwable $throwable
     */
    public function onException(\Throwable $throwable): void
    {
        $this->writeJson(400,'请求不合法');
    }


}