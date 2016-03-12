<?php
namespace Admin\Model;

use Admin\Common\CommonJson;
use Think\Model;

/**
 * 角色权限关联
 * @package Admin\Model
 */
class AuthorityGrantModel extends Model
{

    /**
     * 为这个角色授权
     * @param $roleId   角色ID
     * @param $authorityIdList  授权列表
     * @return bool 是否成功
     */
    public function doGrant($roleId, $authorityIdList)
    {
        $authority = D("authority");
        $authorityGrant = D("authority_grant");
        $authorityList = $authority->select();
        $authorityIdAllList = array();
        //递归出父节点,一起授权
        foreach ($authorityIdList as $authorityId) {
            $authorityIdAllList = $this->getParentAuthority($authorityId, $authorityList, $authorityIdAllList);
        }
        $this->startTrans();
        try {
            $n = $authorityGrant->where("role_id=$roleId")->delete();
            foreach ($authorityIdAllList as $authorityId) {
                $authorityGrant->create();
                $authorityGrant->role_id = $roleId;
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

    /**
     * 递归出这节点上的所有父节点
     * @param $authorityId  当前节点
     * @param $authorityList 全部数据列表
     * @param $authorityIdAllList 传进去累加的节点列表
     * @return array 累加起来的节点ID列表
     */
    private function getParentAuthority($authorityId, $authorityList, $authorityIdAllList)
    {
        $authority = null;
        foreach ($authorityList as $itemAuthority) {
            if ($itemAuthority["id"] == $authorityId) {
                $authority = $itemAuthority;
                break;
            }
        }
        if (!is_null($authority)) {
            if (!in_array($authorityId, $authorityIdAllList)) {
                array_push($authorityIdAllList, $authorityId);
            }
            if ($authority["parent_id"] != -1) {
                $authorityIdAllList = $this->getParentAuthority($authority["parent_id"], $authorityList, $authorityIdAllList);
            }
        }
        return $authorityIdAllList;
    }
}
