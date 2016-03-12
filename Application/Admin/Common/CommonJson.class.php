<?php

namespace Admin\Common;


/**
 * 公用JsTree的Json数据
 * @package Admin\Common
 */
class CommonJson
{
    /**
     *  匿名类对象数组，格式[{id:"",text:"",parent:""}]
     * @param $dataList CommonJson数组对象
     * @param $rootId 最顶级ID，默认为-1
     * @return array 对象Json
     */
    static function JsTreeRecursive($dataList, $rootId)
    {
        $childrenList = array();
        foreach ($dataList as $data) {
            if ($data["parent"] == $rootId) {
                $children = CommonJson::CreateChildren($data, $dataList);
                array_push($childrenList, $children);
            }
        }
        return $childrenList;

    }

    /**
     * 创建子节点
     * @param $node 当前节点
     * @param $dataList CommonJson数组对象
     * @return JsTreeJson 为这个节点添加子节点
     */
    static function CreateChildren($node, $dataList)
    {
        //找出父节点是该节点的列表
        $nodeList = array();
        foreach ($dataList as $data) {
            if ($data["parent"] == $node["id"]) {
                array_push($nodeList, $data);
            }
        }
        //没有子节点了,那么就不用再往下构建了
        $childrenList = array();
        if (count($nodeList) == 0) {
            $childrenList["id"] = $node["id"];
            $childrenList["text"] = $node["text"];
            $childrenList["children"] = array();
            return $childrenList;
        } else {
            $childrenList["id"] = $node["id"];
            $childrenList["text"] = $node["text"];
            $children = array();
            //循环递归构建子节点树
            foreach ($nodeList as $data) {
                $newNode = CommonJson::CreateChildren($data, $dataList);
                array_push($children, $newNode);
            }
            $childrenList["children"] = $children;
            return $childrenList;
        }
    }
}

?>