<?php
namespace Admin\Controller;


use Admin\Model;

/**
 *  角色管理
 * @package Admin\Controller
 */
class RoleController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function content()
    {
        $role = M("Role");
        $list = $role->select();
        $this->assign("list", $list);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function doAdd()
    {
        $role = D("Role");
        if (!$role->create()) {
            $this->errorReturn($role->getError());
        } else {
            $authorityIdString = I("authority_id");
            $authorityIdList = explode(",", $authorityIdString);
            $state = $role->addInfo($authorityIdList);
            if ($state) {
                $this->successReturn("添加角色成功！", "refresh");
            } else {
                $this->errorReturn("添加角色失败！", "refresh");
            }
        }
    }

    public function edit()
    {
        $id = I("id");
        $role = D("Role");
        $data = $role->where("id=$id")->find();
        $this->assign("info", $data);
        $this->display();
    }

    public function doEdit()
    {
        $role = D("Role");
        if (!$role->create()) {
            $this->errorReturn($role->getError());
        } else {
            $role->where("id=" . $role->id)->save();
            $this->successReturn("添加角色成功！","refresh");
        }

    }

    public function doDelete()
    {
        $id = I(id);
        $role = D("Role");
        $state = $role->doDelete($id);
        if ($state) {
            $this->successReturn("添加角色成功！","refresh");
        } else {
            $this->errorReturn("添加角色失败！","refresh");
        }
    }

    public function grant()
    {
        $id = I("id");
        $role = D("Role");
        $authorityGrant = D("authority_grant");
        $role->where("id=$id")->find();
        $authorityInfoList = $authorityGrant->field("authority_id")->where("role_id=$id")->select();
        $authorityIdList = "";
        foreach ($authorityInfoList as $authorityInfo) {
            $authorityIdList = $authorityIdList . $authorityInfo["authority_id"] . ",";
        }
        $authorityIds = trim($authorityIdList, ',');
        $this->assign("authorityIds", $authorityIds);
        $this->assign("id", $id);
        $this->display();
    }

    public function doGrant()
    {
        $roleId = I("roleId");
        $authorityIds = I("grantList");
        $authorityGrant = D("authority_grant");
        if (empty($authorityIds)) {
            $authorityGrant->where("role_id=$roleId")->delete();
            //清除缓存
            $this->deleteAuthority();
            $this->successReturn("授权成功！");
        } else {
            $authorityIdList = explode(',', $authorityIds);
            $state = $authorityGrant->doGrant($roleId, $authorityIdList);
            if ($state) {
                //清除缓存
                $this->deleteAuthority();
                $this->successReturn("授权成功！");
            } else {
                $this->successReturn("授权失败！");
            }
        }
    }

}