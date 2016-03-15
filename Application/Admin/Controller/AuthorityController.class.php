<?php
namespace Admin\Controller;


use Admin\Model;

/**
 *  用户权限查单
 * @package Admin\Controller
 */
class AuthorityController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function menuList()
    {
        $info = new Model\AuthorityModel();
        $result = $info->recursive();
        $this->ajaxReturn($result);
    }

    public function menuDetail()
    {
        $id = I("id");
        $user = M("Authority"); // 实例化User对象
        $node = $user->where("id=$id")->find();
        if ($node["parent_id"] == -1) {
            $node["parent_menu_name"] = "顶级菜单";
        } else {
            $parentNode = $user->where(array('parent_id' => $node["parent_id"]))->find();
            if (isset($parentNode)) {
                $node["parent_menu_name"] = $parentNode["menu_name"];
            } else {
                $node["parent_menu_name"] = "没有找到父节点,树结构出错!";
            }
        }
        $this->assign("info", $node);
        $this->display();
    }

    public function add()
    {
        $id = I("id");
        $authority = new Model\AuthorityModel();
        $info = $authority->nodeInfo($id);
        $this->assign("info", $info);
        $this->display();

    }

    public function doAdd()
    {
        $authority = D("Authority");
        if (!$authority->create()) {
            $this->ajaxReturn(array("code" => 0, "message" => $authority->getError()));
        } else {
            if (I("is_enable_link") && empty(I("link"))) {
                $this->errorReturn("选择链接菜单时，必须填写菜单地址！");
            }
            $state = $authority->addNode();
            if ($state) {
                $this->successReturn("添加菜单成功", "refresh");
            } else {
                $this->errorReturn("添加菜单失败！", "refresh");
            }

        }
    }

    public function doDelete()
    {
        $id = I("id");
        $authority = D("Authority");
        $state = $authority->deleteAuthority($id);
        if ($state) {
            $this->successReturn("删除菜单成功！", "refresh");
        } else {
            $this->errorReturn("删除菜单失败", "refresh");
        }
    }

    public function edit()
    {

        $id = I("id");
        $authority = D("Authority");
        $info = $authority->where("id=$id")->find();
        $list = $authority->field("id, menu_name")->select();
        $data = array();
        foreach ($list as $key => $val) {
            $data[$val["id"]] = $val["menu_name"];
        }
        array_unshift($list, array("id" => -1, "menu_name" => "顶级菜单"));
        $this->assign("list", $data);
        $this->assign("is_enable_link_list", array(1 => "是", 0 => "否"));
        $this->assign("info", $info);
        $this->display();

    }

    public function doEdit()
    {
        $authority = D("Authority");
        if (!$authority->create()) {
            $this->ajaxReturn(array("code" => 0, "message" => $authority->getError()));
        } else {
            if (I("is_enable_link") && empty(I("link"))) {
                $this->errorReturn("选择链接菜单时，必须填写菜单地址！");
            }
            $state = $authority->updateNode();
            if ($state) {
                $this->successReturn("修改菜单成功", "refresh");
            } else {
                $this->errorReturn("添加菜单失败！", "refresh");
            }

        }
    }
}