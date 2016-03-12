<?php
namespace Admin\Controller;


/**
 * 公共模块,提供模板支持
 * @package Admin\Controller
 */
class PublicController extends  BaseController
{
    public function layout()
    {
        $this->display();
    }
}