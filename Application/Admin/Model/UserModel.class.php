<?php
namespace Admin\Model;

use Think\Exception;
use Think\Model;

class UserModel extends Model
{
    protected $_validate = array(
        array('name', 'require', '用户名不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('name', '1,20', '用户名不能不能超过20个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('name', '', '帐号名称已经存在！', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
    );

    public function content($name = '', $page_index = 1)
    {
        //常规分页设置
        if ($page_index == 0) $page_index = 1;
        $page_size = PAGE_SIZE;
        $offset = ($page_index - 1) * $page_size;

        $user = D('user');
        $map["name"] = array("like", "%" . $name . "%");
        $page_count = $user->where($map)->count();
        $list = $user->where($map)->limit("$offset, $page_size")->order("id desc")->select();

        return array("page_count" => $page_count, "list" => $list);
    }


    /**
     * 根据用户ID删除用户,并删除这个用户下的所有角色关联 user_role
     * @param $userId
     */
    public function deleteUser($id)
    {
        $this->startTrans();
        try {
            $n = $this->where("id=$id")->delete();
            $userRole = D("UserRole");
            $n = $userRole->where("user_id=$id")->delete();
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }
}
