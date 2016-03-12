<?php
namespace Admin\Model;

use Think\Model;

class RoleModel extends Model
{
    protected $_validate = array(
        array('name', 'require', '角色名称不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('name', '1,50', '角色名称不能不能超过20个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('name', '', '角色名称已经存在！', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('descript', '0,200', '角色描述不能不能超过50个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
    );

    public function addInfo($authorityIdList)
    {
        $data = $this->data();
        $data["create_time"] = date('Y-m-d H:i:s', time());
        $this->startTrans();
        try {
            $id = $this->data($data)->add();
            foreach ($authorityIdList as $authorityId) {
                $authorityGrant = M("authority_grant");
                $authorityGrant->create();
                $authorityGrant->role_id = $id;
                $authorityGrant->authority_id = $authorityId;
                $n = $authorityGrant->add();
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }

    public function doDelete($id)
    {
        $this->startTrans();
        try{
            $n = $this->delete($id);
            $authority_grant = D("authority_grant");
            $n = $authority_grant->where("role_id=$id")->delete();
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }

}
