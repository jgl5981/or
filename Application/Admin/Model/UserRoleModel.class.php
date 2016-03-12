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
    public function getRoleListByUserId($userId)
    {

        $list =  $this->query("SELECT o.* FROM or_user_role r INNER JOIN or_role o ON r.`role_id` = o.`id` WHERE r.`user_id` = %d", array($userId));
        return $list;
    }
}
