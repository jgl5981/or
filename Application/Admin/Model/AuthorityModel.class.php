<?php
namespace Admin\Model;

use Admin\Common\CommonJson;
use Think\Model;

/**
 * 权限对象模型
 * @package Admin\Model
 */
class AuthorityModel extends Model
{
    /**
     * @var array
     */
    protected $_validate = array(
        array('menu_name', 'require', '菜单名称不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('menu_name', '1,20', '菜单名称不能不能超过20个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('menu_name', '', '菜单名称已经存在！', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
    );


    /**
     * 返回这个节点的ID跟节点名称
     * @param int $id
     * @return array
     */
    public function nodeInfo($id = 0)
    {
        $info = array();
        if (empty($id) || $id == "-1") {
            //顶级节点为新建节点父节点
            $info["parent_id"] = -1;
            $info["parent_menu_name"] = "顶级菜单";
        } else {
            $user = M("Authority"); // 实例化User对象
            $node = $user->where("id=$id")->find();
            if (isset($node)) {
                //当前选中节点为新建节点父节点
                $info["parent_id"] = $node["id"];
                $info["parent_name"] = $node["menu_name"];
            }
        }
        return $info;
    }

    /**
     * 构建节点树
     * @return array
     */
    public function recursive()
    {
        $authority = D('authority');
        $list = $authority->select();
        $dataList = array();
        //封装成节点数对象
        foreach ($list as $item) {
            $commonJson = array();
            $commonJson["id"] = $item["id"];
            $commonJson["text"] = $item["menu_name"];
            $commonJson["parent"] = $item["parent_id"];
            array_push($dataList, $commonJson);
        }
        //添加树的根节点
        $model = array();
        $model["id"] = "-1";
        $model["text"] = "所有权限菜单";
        $model["state"] = array("opened" => true);
        //把数据对象构建成这个根节点的子节点
        $model["children"] = CommonJson::JsTreeRecursive($dataList, "-1");
        return $model;
    }


    /**
     * 添加节点
     * @return bool
     */
    public function addNode()
    {
        /* 获取数据对象 */
        $data = $this->data();
        $data["path"] = "-1";
        $data["create_time"] = date('Y-m-d H:i:s', time());

        $this->startTrans();
        try {
            $id = $this->add($data);

            $path = "/$id";
            if ($data["parent_id"] != -1) {
                $parentId = $data["parent_id"];
                $parentNode = $this->where("id=$parentId")->find();
                $path = $parentNode["path"] . "/$id";
            }
            $data["path"] = $path;
            $n = $this->where("id=$id")->save($data);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }

    }

    /**
     * 更新节点
     * @return bool
     */
    public function updateNode()
    {
        $data =$this->data();
        try {
            $id = $data["id"];
            $parentId = $data["parent_id"];
            $path = "/$id";
            $info = $this->where("id=$id")->find();
            if ($info["parent_id"] != $data["parent_id"]) {
                $parentInfo = $this->where("id=$parentId")->find();
                if (isset($parentInfo)) {
                    $path = $parentInfo["path"] . "/" . $id;
                }
            }
            $data["path"] = $path;
            $this->where("id=$id")->save($data);
            $data->commit();
            return true;
        } catch( \Exception $e) {
            $this->rollback();
            return false;
        }


    }

    public function getAuthorityByuserId($userId)
    {
        $list = $this->query("SELECT a.* FROM or_authority a
                      INNER JOIN or_authority_grant g ON a.`id` = g.`authority_id`
                      INNER JOIN or_role r ON r.`id` = g.`role_id`
                      INNER JOIN or_user_role l ON l.`role_id` = r.`id`   WHERE l.`user_id` = %d", $userId);

        return $list;

    }


}
