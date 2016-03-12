<?php
namespace Admin\Widget;

use Think\Controller;

/**
 *  提供管理头部插件
 * @package Admin\Widget
 */
class HeaderWidget extends \Admin\Controller\BaseController
{

    public function header()
    {
        $user = $this->getUser();
        $this->assign("user", $user);
        $this->display("Widget:header");
    }
}
?>