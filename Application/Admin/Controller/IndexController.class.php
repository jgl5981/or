<?php
namespace Admin\Controller;

class IndexController extends  BaseController
{
    public function index()
    {
        $user = D('user');
        $list = $user->select();
        $this->assign('list', $list);
        $this->display();
    }
}