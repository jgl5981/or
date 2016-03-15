<?php

namespace Admin\Controller;

use Think\Controller;


/**
 * 基类,所有Admin模块的控制器必须从这个类继承
 * @package Admin\Controller
 */
class BaseController extends Controller
{

    public function  index()
    {
        $this->display();
    }

    //region 用户存入Session, 从session获取用户, 注销用户
    /**
     * @var string 用户Session key
     */
    private static $session_user = "user";

    public function  saveUser($user)
    {
        if (isset($user)) {
            $this->user = $user;
            session($this->session_user, $user);
        }
    }

    public function getUser()
    {
        $user = session($this->session_user);
        if (is_null($user)) {
            $this->redirect("/Admin/Index/login");
        } else {
            return $user;
        }
    }

    public function deleteUser()
    {
        session($this->session_user, null);
    }
    //endregion

    //region  封装一部分通用方法
    /**
     * 分页封装
     * @param $list 内容列表
     * @param $page_count 数据库总条数
     */
    public function pager($list, $page_count)
    {
        $page_index = I("page_index");

        if ($page_index == null) $page_index = 1;
        $this->assign('page_index', $page_index);
        $this->assign('page_count', $page_count);
        $this->assign('list', $list);
    }


    /**
     * 通用返回数据口
     * @param $code 状态代码 -1/0/1, 重定向/失败/成功
     * @param $sMessage 成功显示信息 状态代码 1生效
     * @param $fMessage 失败显示信息 状态代码 0生效
     * @param $callback 客户端需要回调的函数 没有传"",函数入参data,AjaxJson,xhr,
     * @param $data 客户端需要回调的函数 没有传"",传输数据(json)
     * @param $redirect 重定向地址 状态代码-1生效
     */
    public function jsonReturn($code = 0, $sMessage = "操作成功", $fMessage = "操作失败", $callback = "", $data = "", $redirect = "")
    {
        //大于1 都都算是成功状态
        $code = $code > 1 ? 1 : $code;
        //坑爹的三目运算符,我是日了狗了
        $message = "";
        if($code == 1) $message = $sMessage;
        if($code == 0) $message = $fMessage;

        $this->ajaxReturn(array("code" => $code, "message" => $message, "callback" => $callback, "data" => $data, "redirect" => $redirect));
    }


    /**
     * 返回成功信息
     * @param string $message 成功显示信息
     * @param string $callback 客户端需要回调的函数 没有传"",函数入参data,AjaxJson,xhr,
     * @param string $data 客户端需要回调的函数 没有传"",传输数据(json)
     */
    public function successReturn($message = "操作成功", $callback = "", $data = "")
    {
        $this->ajaxReturn(array("code" => 1, "message" => $message, "callback" => $callback, "data" => $data));
    }

    /**
     * 返回失败信息
     * @param string $message 失败显示信息
     * @param string $callback 客户端需要回调的函数 没有传"",函数入参data,AjaxJson,xhr,
     * @param string $data 客户端需要回调的函数 没有传"",传输数据(json)
     */
    public function errorReturn($message = "操作失败", $callback = "", $data = "")
    {
        $this->ajaxReturn(array("code" => 0, "message" => $message, "callback" => $callback, "data" => $data));
    }


    /**
     * 跳转
     * @param string $message 重定向跳转时显示的信息
     * @param string $redirect 重定向地址
     */
    public  function  redirectReturn($message = "操作失败", $redirect = "")
    {
        $this->ajaxReturn(array("code" => -1, "message" => $message, "redirect" => $redirect));
    }
    //endregion
}