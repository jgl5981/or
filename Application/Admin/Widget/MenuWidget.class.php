<?php
namespace Admin\Widget;

use Think\Controller;


/**
 * 提供管理左侧导航插件
 * @package Admin\Widget
 */
class MenuWidget extends \Admin\Controller\BaseController
{
    public function menu()
    {
        $dataList = array();
        $authorityList = $this->getAuthority();
        foreach ($authorityList as $authority) {
            if ($authority["parent_id"] == "-1") {
                array_push($dataList, $this->createChildren($authority, $authorityList));
            }
        }
        $this->assign("dataList", $dataList);
        $this->display("Widget:menu");
    }

    private function  createChildren($authority, $authorityList)
    {
        $nextList = array();
        foreach ($authorityList as $authorityItem) {
            if ($authorityItem["parent_id"] == $authority["id"])
                array_push($nextList, $authorityItem);
        }
        if (count($nextList) > 0) {
            $tempList = array();
            foreach ($nextList as $next) {
                array_push($tempList, $this->createChildren($next, $authorityList));
            }
            $authority["child_menu"] = $tempList;
        }
        $authority["like_list"] = explode('/', $authority["link"]);
        return $authority;


    }
}

?>