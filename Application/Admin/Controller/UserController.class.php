<?php
namespace Admin\Controller;

use Admin\Model;


/**
 * 用户管理
 * @package Admin\Controller
 */
class UserController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function content()
    {
        $page_index = I("page_index");
        $name = I("name");
        $user = D('User');
        $map["name"] = array("like", "%" . $name . "%");
        $page_count = $user->where($map)->count();
        $list = $user->where($map)->page($page_index, PAGE_SIZE)->order("id desc")->select();
        $this->pager($list, $page_count);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function doAdd()
    {
        $user = D("User");
        if (!$user->create()) {
            $this->errorReturn($user->getError());
        } else {
            $user->password = md5("1");
            $name = $user->name;
            $code = $user->add();
            $this->successReturn("添加成功", "refresh", array("message" => $message = "您的用户名： $name , 密码：1。"));

        }
    }

    public function doResetPassword()
    {
        $id = I("id");
        if (empty($id)) {
            $this->errorReturn("参数有误");
        } else {
            $user = M("User"); // 实例化User对象
            $info = $user->where("id=$id")->find();
            $info["password"] = md5(1);
            $code = $user->save($info);
            $this->successReturn("密码重置成功", "refresh", array("message" => $message = "您的用户名：" . $info["name"] . ", 密码：1。"));
        }
    }

    public function doDelete()
    {
        $id = I("id");
        $user = new Model\UserModel();
        $state = $user->deleteUser($id);
        if ($state) {
            $this->successReturn("删除成功", "refresh");
        } else {
            $this->errorReturn("删除失败", "refresh");
        }

    }
}