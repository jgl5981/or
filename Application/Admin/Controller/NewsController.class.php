<?php
namespace Admin\Controller;


use Admin\Common\Config;
use Admin\Common\Util;
use Admin\Model;

/**
 *  新闻
 * @package Admin\Controller
 */
class NewsController extends BaseController
{
    public function index()
    {
        $authority = D("NewsType");
        $list = $authority->field("id, name")->select();
        $typeList = array_column($list,'name', 'id');
        $this->assign("list", $typeList);
        $this->display();

    }

    public function content()
    {
        $page_index = I("page_index");
        $title = I("title");
        $type_id = I("type_id");

        $user = D('News');
        if (!empty($title)) {
            $map["title"] = array("like", "%" . $title . "%");
        }
        if (!empty($type_id) && $type_id != -1) {
            $map["type_id"] = array("eq", $type_id);
        }
        $page_count = $user->where($map)->count();
        $list = $user->where($map)->page($page_index, PAGE_SIZE)->order("id desc")->select();
        $this->pager($list, $page_count);
        $this->display();
    }

    public function add()
    {
        $newsType = D("NewsType");
        $list = $newsType->field("id, name")->select();
        $typeList = array_column($list,'name', 'id');
        $this->assign("list", $typeList);
        $this->display();
    }

    public function doAdd()
    {
        $news = D("News");
        if (!$news->create()) {
            $this->errorReturn($news->getError());
        } else {
            $newsType = D("NewsType");
            $newsTypeData = $newsType->where("id=" . $news->type_id)->find();
            $title = $newsType->title;
            if (empty($newsTypeData)) {
                $this->errorReturn("新闻类别有误");
            } else {
                $news->doAdd($newsTypeData["name"], $this->getUser()["name"]);
                $this->successReturn("添加成功", "refresh");
            }
        }
    }

    public function edit()
    {
        $id = I("id");
        $news = D("News");
        $newsImage = D("NewsImage");
        $newsType = D("NewsType");

        $list = $newsType->field("id, name")->select();
        $typeList = array_column($list,'name', 'id');
        $info = $news->where("id=$id")->find();

        $imageList = $newsImage->where("news_id=$id")->select();
        $imageData = array();
        $imageString = "";
        foreach ($imageList as $image) {
            $path = Config::$uploadConfig["lookPath"] . $image["path"];
            $image["path"] = $path;
            $imageString = $imageString . $path . ",";
            array_push($imageData, $image);
        }
        $info["image"] = trim($imageString, ",");
        $this->assign("list", $typeList);
        $this->assign("images", json_encode($imageData));
        $this->assign("info", $info);
        $this->display();
    }

    public function doEdit()
    {
        $news = D("News");
        if (!$news->create()) {
            $this->errorReturn($news->getError());
        } else {
            $newsType = D("NewsType");
            $newsTypeData = $newsType->where("id=" . $news->type_id)->find();
            $title = $newsType->title;
            if (empty($newsTypeData)) {
                $this->errorReturn("新闻类别有误");
            } else {
                $news->doEdit($newsTypeData["name"], $this->getUser()["name"]);
                $this->successReturn("修改成功", "refresh");
            }
        }
    }

    public function detail()
    {
        $id = I("id");
        $news = D("News");
        $newsImage = D("NewsImage");
        $info = $news->where("id=$id")->find();
        $imageList = $newsImage->where("news_id=$id")->select();
        $this->assign("list", $imageList);
        $this->assign("info", $info);
        $this->display();
    }

    public function doDelete()
    {
        $id = I("id");
        $news = D("News");
        $newsImage = D("NewsImage");
        $news->where("id=$id")->delete();
        $newsImage->where("news_id=$id")->delete();
        $this->successReturn("删除成功!", "refresh");

    }

    //region 图片操作
    public function uploadImage()
    {
        $upload = new \Think\Upload(Config::$uploadConfig);// 实例化上传类
        $info = $upload->upload();
        if (!$info) {
            $this->errorReturn($upload->getError());
        } else {// 上传成功
            $data = array();
            foreach ($info as $file) {
                array_push($data, (Config::$uploadConfig["lookPath"] . $file["savepath"] . $file["savename"]));
            }
            $this->successReturn('上传成功！', "", $data);
        }
    }

    public function doAddImage()
    {
        $upload = new \Think\Upload(Config::$uploadConfig);// 实例化上传类
        $info = $upload->upload();
        if (!$info) {
            $this->errorReturn($upload->getError());
        } else {
            // 上传成功
            $data = array();
            foreach ($info as $file) {
                array_push($data, (Config::$uploadConfig["lookPath"] . $file["savepath"] . $file["savename"]));
            }
            $this->successReturn('上传成功！', "", $data);
        }

    }

    public function doDeleteImage()
    {
        $id = I("id");
        $state = unlink("." . $id);
        if ($state) {
            $this->successReturn("删除成功");
        } else {
            $this->errorReturn("删除失败");
        }
    }
    //endregion

}
