<?php

namespace Admin\Controller;

use Admin\Common\Config;
use Think\Controller;


/**
 * 基类,所有Admin模块的控制器必须从这个类继承
 * @package Admin\Controller
 */
class BaseController extends Controller
{
    protected function _initialize()
    {
        $user = session(Config::$session_user);
        if (empty($user) && strtoupper(CONTROLLER_NAME) != "INDEX") {
            $redirect = U("/Admin/Index/login");
            $this->redirectReturn("成功退出...", $redirect);
        } else {
            if (strtoupper(CONTROLLER_NAME) != "INDEX") {
                $authorityList = $this->getAuthority();
                $currentController = MODULE_NAME . "/" . CONTROLLER_NAME;
                $hasAuthority = false;
                foreach ($authorityList as $authority) {
                    if (stripos($authority["link"], $currentController) > -1) {
                        $hasAuthority = true;
                        break;
                    }
                }
                if (!$hasAuthority) {
                    $redirect = U("/Admin/Index/noAuthority");
                    $this->redirectReturn("权限不足...", $redirect);
                }
            }
        }
    }

    public function deleteAuthority()
    {
        $user = session(Config::$session_user);
        S(Config::$cache_authority, null);
    }

    public function getAuthority()
    {
        $user = session(Config::$session_user);
        $authority = S(Config::$cache_authority);
        if (empty($authority)) {
            $authority = D("authority");
            $authority = $authority->getAuthorityByuserId($user["id"]);
            S(Config::$cache_authority, $authority);
        }
        return $authority;
    }

    //region session
    public function  index()
    {
        $this->display();
    }

    public function  saveUser($user)
    {
        if (isset($user)) {
            session(Config::$session_user, $user);
        }
    }

    public function getUser()
    {
        $user = session(Config::$session_user);
        if (empty($user)) {
            $this->redirect("/Admin/Index/login");
        } else {
            return $user;
        }
    }

    public function deleteUser()
    {
        session(Config::$session_user, null);
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
        if ($code == 1) $message = $sMessage;
        if ($code == 0) $message = $fMessage;

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
    public function  redirectReturn($message = "操作失败", $redirect = "")
    {
        $this->ajaxReturn(array("code" => -1, "message" => $message, "redirect" => $redirect));
    }
    //endregion
}