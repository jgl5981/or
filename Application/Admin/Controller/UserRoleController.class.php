<?php
namespace Admin\Controller;

use Admin\Common\AjaxJson;
use Admin\Model;

/**
 * 用户角色授权
 * @package Admin\Controller
 */
class UserRoleController extends BaseController
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
        $userList = $user->where($map)->page($page_index, PAGE_SIZE)->order("id desc")->select();
        $userRole = D("UserRole");
        $dataList = array();
        foreach ($userList as $user) {
            $user["role"] = $userRole->getRoleListByUserId($user["id"]);
            array_push($dataList, $user);
        }
        $this->pager($dataList, $page_count);
        $this->display();
    }

    public function userRole()
    {
        $userId = I("userId");
        $userRole = D("UserRole");
        $role = D("Role");
        $userRoleList = $userRole->getRoleListByUserId($userId);
        $dataList = array();
        $roleList = $role->select();
        foreach ($roleList as $RoleItem) {
            $checked = false;
            foreach ($userRoleList as $userRoleItem) {
                if ($userRoleItem["id"] == $RoleItem["id"]) {
                    $checked = true;
                    break;
                }
            }
            $RoleItem["checked"] = $checked;
            $RoleItem["userId"] = $userId;
            array_push($dataList, $RoleItem);
        }
        $this->assign("dataList", $dataList);
        $this->display();
    }

    public function doGrantRole()
    {
        $data["role_id"] = I("role_id");
        $data["user_id"] = I("user_id");
        $userRole = D("user_role");
        if (!$userRole->create($data)) {
            $this->errorReturn($userRole->getError());
        } else {
            $n = $userRole->add();
            //清除缓存
            $this->deleteAuthority();
            $this->successReturn("用户角色授权成功", "refresh");
        }
    }

    public function doRemoveGrantRole()
    {
        $user_id = I("user_id");
        $role_id = I("role_id");
        $userRole = D("UserRole");
        $n = $userRole->where("role_id = $role_id AND user_id = $user_id")->delete();
        //清除缓存
        $this->deleteAuthority();
        $this->successReturn("取消用户角色授权成功", "refresh");
    }

}