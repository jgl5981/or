<?php
namespace Admin\Widget;

use Think\Controller;

class MenuWidget extends \Admin\Controller\BaseController
{
    public function menu()
    {
        $userId = 119;
        $authority = D("authority");
        $dataList = array();
        $authorityList = $authority->getAuthorityByuserId($userId);
        foreach ($authorityList as $authority) {
            if ($authority["parent_id"] == "-1") {
                array_push($dataList, $this->createChildren($authority, $authorityList));
            }
        }
        $this->assign("dataList", $dataList);
        $this->display("Widget:menu");
    }

    public function  createChildren($authority, $authorityList)
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