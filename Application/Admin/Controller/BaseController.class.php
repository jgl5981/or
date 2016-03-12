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


}