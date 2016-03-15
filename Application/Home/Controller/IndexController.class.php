<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{
    public function index()
    {

        $d = 1;
        $code = 1;
        $message = $code ==2 ? $code == 1 ? $code == 0 ? "0": "1": "2" : "3";
        echo $message;
        exit;
        $this->display();
    }
}