<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */

namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->response()->write('hello world');
        $data = [
            'id' => 1,
            'name' => 'darian'
        ];
        return $this->writeJson('200',"成功",$data);
        // TODO: Implement index() method.
    }

    public function video(){
        $data = [
            'id' => 1,
            'name' => 'darian',
            'param' => $this->request()->getQueryParam()
        ];
        return $this->writeJson('200',"成功",$data);
    }
}