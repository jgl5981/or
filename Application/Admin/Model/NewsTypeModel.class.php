<?php
namespace Admin\Model;

use Admin\Common\CommonJson;
use Admin\Common\Util;
use Think\Model;

/**
 * 新闻类别模型
 * @package Admin\Model
 */
class NewsTypeModel extends Model
{

    /**
     * @var array
     */
    protected $_validate = array(
        array('name', 'require', '新闻类别名称不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '1,20', '新闻类别名称不能不能超过20个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('name', '', '新闻类别名称已经存在！', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
    );

    /**
     * 构建节点树
     * @return array
     */
    public function recursive()
    {
        $authority = D('NewsType');
        $list = $authority->select();
        $dataList = array();
        //封装成节点数对象
        foreach ($list as $item) {
            $commonJson = array();
            $commonJson["id"] = $item["id"];
            $commonJson["text"] = $item["name"];
            $commonJson["parent"] = $item["parent_id"];
            array_push($dataList, $commonJson);
        }
        //添加树的根节点
        $model = array();
        $model["id"] = "-1";
        $model["text"] = "顶级新闻类别";
        $model["state"] = array("opened" => true);
        //把数据对象构建成这个根节点的子节点
        $model["children"] = CommonJson::JsTreeRecursive($dataList, "-1");
        return $model;
    }



}
