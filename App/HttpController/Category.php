<?php
/**
 * Created by PhpStorm.
 * User: Ty_Ro
 * Date: 2019/2/10
 * Time: 21:16
 */
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;

class Category extends Controller
{
    public function index()
    {
        $this->response()->write('hello world');

        // TODO: Implement index() method.
    }
}