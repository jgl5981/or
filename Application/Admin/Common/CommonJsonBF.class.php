<?php

namespace Admin\Common;


/**
 * 公用JsTree的Json数据
 * @package Admin\Common
 */
class CommonJson
{
    /**
     * 编号
     */
    public $id;
    /**
     * 文本
     */
    public $text;
    /**
     * 父节点
     */
    public $parent;

    /**
     *  匿名类对象数组，格式[{id:"",text:"",parent:""}]
     * @param $dataList CommonJson数组对象
     * @param $rootId 最顶级ID，默认为-1
     * @return array 对象Json
     */
    static function jsTreeRecursive($dataList, $rootId)
    {
        $json = array();
        foreach ($dataList as $data) {
            if ($data->parent == $rootId) {
                $item = CommonJson::createChildren($data, $dataList);
                array_push($json, $item);
            }
        }
        return $json;

    }

    /**
     * 创建子节点
     * @param $node 当前节点
     * @param $dataList CommonJson数组对象
     * @return JsTreeJson 为这个节点添加子节点
     */
    static function createChildren($node, $dataList)
    {
        $nodeList = array();
        foreach ($dataList as $data) {
            if ($data->parent == $node->id) {
                array_push($nodeList, $data);
            }
        }
        if (count($nodeList) == 0) {
            $jsTreeJson = new JsTreeJson();
            $jsTreeJson->id = $node->id;
            $jsTreeJson->text = $node->text;
            $jsTreeJson->children = new JsTreeJson();
            return $jsTreeJson;
        } else {
            $jsTreeJson = new JsTreeJson();
            $jsTreeJson->id = $node->id;
            $jsTreeJson->text = $node->text;
            $jsTreeJson->children = new JsTreeJson();
            $child = array();
            foreach ($nodeList as $data) {
                $snode = CommonJson::createChildren($data, $dataList);
                array_push($child, $snode);
            }
            $jsTreeJson->children = $child;
            return $jsTreeJson;
        }
    }
}

?>