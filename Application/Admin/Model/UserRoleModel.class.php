<?php
namespace Admin\Model;

use Admin\Common\CommonJson;
use Think\Model;

/**
 * 用户角色关系
 * @package Admin\Model
 */
class UserRoleModel extends Model
{

    const TABLE_NAME = "user_role";
    /**
     * 返回这个用户下的所有角色
     * @param $userId
     * @return mixed
     */
    public function getRoleListByUserId($userId)
    {
        $list =  $this->query("SELECT o.* FROM or_user_role r INNER JOIN or_role o ON r.`role_id` = o.`id` WHERE r.`user_id` = %d", array($userId));
        return $list;
    }



}
