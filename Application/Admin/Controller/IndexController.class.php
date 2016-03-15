<?php
namespace Admin\Controller;


/**
 * 首页 并提供用户登录,退出功能
 * @package Admin\Controller
 */
class IndexController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function login()
    {
        $this->display();
    }

    public function doLogin()
    {
        $name = I('name');
        $password = md5(I('password'));
        if (empty($name) || empty($password)) {
            $this->errorReturn("账号密码不能为空。");
        }
        $user = D("User");
        $data = $user->where("name='$name'")->find();
        if ($data['password'] != $password) {
            $this->errorReturn("密码不正确，请重新输入。");
        }

        $this->saveUser($data);
        $redirect = U("/Admin");
        $this->redirectReturn("登入成功！正在进行跳转...", $redirect);
    }

    public function logout()
    {
        $this->deleteUser();
        $redirect = U("login");
        $this->redirect("成功退出...", $redirect);
    }
}