<?php
namespace Admin\Model;

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

}
