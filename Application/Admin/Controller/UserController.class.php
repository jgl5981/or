<?php
namespace Admin\Controller;

use Admin\Common\AjaxJson;
use Admin\Model;

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

        $user = D('user');
        $map["name"] = array("like", "%" . $name . "%");
        $page_count = $user->where($map)->count();
        $list = $user->where($map)->page($page_index, PAGE_SIZE)->order("id desc")->select();

        $this->assign('page_index', $page_index);
        $this->assign('page_count', $page_count);
        $this->assign('list', $list);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function doAdd()
    {
        $ajaxJson = new AjaxJson();
        $user = D("User");
        if (!$user->create()) {
            $this->ajaxReturn(array("code" => 0, "message" => $user->getError()));
        } else {
            $user->password = md5("1");
            $name = $user->name;
            $n = $user->add();
            $this->ajaxReturn(array("code" => 1, "message" => "添加成功", "callback" => "refresh",
                "data" => array("message" => $message = "您的用户名： $name , 密码：1。")));

        }
    }

    public function doResetPassword()
    {
        $id = I("id");
        if (empty($id)) {
            $this->ajaxReturn(array("code" => 0, "message" => "参数有误"));
        } else {
            $user = M("User"); // 实例化User对象
            $info = $user->where("id=$id")->find();
            $info["password"] = md5(1);
            $n = $user->save($info);
            $this->ajaxReturn(array("code" => 1, "message" => "密码重置成功", "callback" => "refreshList",
                "data" => array("message" => $message = "您的用户名：" . $info["name"] . ", 密码：1。")));
        }
    }

    public function doDelete()
    {
        $id = I("id");
        if (!empty($id)) {
            $user = M("User");

            $user->where("id=$id")->delete();
            $this->ajaxReturn(array("code" => 1, "message" => "删除成功", "callback" => "refresh"));
        } else {
            $this->ajaxReturn(array("code" => 0, "message" => "参数有误", "callback" => "refresh"));
        }

    }
}