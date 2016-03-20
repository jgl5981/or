<?php
namespace Admin\Controller;


use Admin\Common\Util;
use Admin\Model;

/**
 *  新闻类别
 * @package Admin\Controller
 */
class NewsTypeController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function content()
    {
        $info = new Model\NewsTypeModel();
        $result = $info->recursive();
        $this->ajaxReturn($result);
    }

    public function detail()
    {
        $id = I("id");
        $info = M("NewsType");
        $newsType = $info->where("id=$id")->find();
        if ($newsType["parent_id"] == -1) {
            $newsType["parentName"] = "顶级新闻类别";
        } else {
            $parentNewsType = $info->where("id=" . $newsType["parent_id"])->find();
            $newsType["parentName"] = $parentNewsType["name"];
        }
        $this->assign("info", $newsType);
        $this->display();

    }

    public function  add()
    {
        $id = I("id");
        $data = array();
        if ($id == -1) {
            $data["parent_id"] = -1;
            $data["parent_name"] = "顶级新闻类别";
        } else {
            $info = D("NewsType");
            $newsType = $info->where("id=$id")->find();
            $data["parent_id"] = $newsType["id"];
            $data["parent_name"] = $newsType["name"];
        }
        $this->assign("info", $data);
        $this->display();
    }

    public function doAdd()
    {
        $newsType = D("NewsType");
        if (!$newsType->create()) {
            $this->ajaxReturn(array("code" => 0, "message" => $newsType->getError()));
        } else {
            $newsType->create_time = Util::now();
            $state = $newsType->add();
            if ($state) {
                $this->successReturn("添加菜单成功", "refresh");
            } else {
                $this->errorReturn("添加菜单失败！", "refresh");
            }

        }
    }

    public function edit()
    {
        $id = I("id");
        $authority = D("NewsType");
        $info = $authority->where("id=$id")->find();
        $list = $authority->field("id, name")->select();
        $data = array();
        foreach ($list as $key => $val) {
            $data[$val["id"]] = $val["name"];
        }
        array_unshift($list, array("id" => -1, "name" => "顶级菜单"));
        $this->assign("list", $data);
        $this->assign("info", $info);
        $this->display();
    }

    public function doEdit()
    {
        $authority = D("NewsType");
        if (!$authority->create()) {
            $this->ajaxReturn(array("code" => 0, "message" => $authority->getError()));
        } else {
            $state = $authority->save();
            if ($state) {
                $this->successReturn("修改菜单成功", "refresh");
            } else {
                $this->errorReturn("添加菜单失败！", "refresh");
            }

        }
    }

    public function doDelete()
    {
        $id = I("id");
        $newsType = D("NewsType");
        $state = $newsType->where("id=$id or parent_id = $id")->delete();
        if ($state) {
            $this->successReturn("删除菜单成功！", "refresh");
        } else {
            $this->errorReturn("删除菜单失败", "refresh");
        }
    }

}