<?php
namespace Admin\Controller;

class LoginController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function doLogin()
    {
        $username = I('post.name');
        $password = md5(I('post.password'));

        if (empty($username) || empty($password)) {
            $data['error'] = 1;
            $data['msg'] = "请输入帐号密码";
            $this->ajaxReturn($data);
        }

        $map = array();
        $map['user'] = $username;
        $map['state'] = 1;

        $user = D("user");

        $userInfo = $user->where($map)->find();

        if ($userInfo['password'] != $password) {
            $data['error'] = 1;
            $data['msg'] = '帐号密码不正确';
            $this->ajaxReturn($data);
        }
        session('user', $userInfo);

        $data['error'] = 0;
        $data['url'] = U('Index/index');
        $this->ajaxReturn($data);
    }
}