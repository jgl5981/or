<?php

namespace Admin\Common;


/**
 *  配置通用类
 * @package Admin\Common
 */
class Config
{
    //图片上传配置
    public static $uploadConfig = array(
        'maxSize' => 3145728,
        'lookPath' => '/Uploads/Picture/',
        'rootPath' => './Uploads/Picture/',
        'savePath' => '',
        'saveName' => array('uniqid', ''),
        'exts' => array('jpg', 'gif', 'png', 'jpeg'),
        'autoSub' => true,
        'subName' => array('date', 'Y-m-d'),
    );

    public static $session_user = "user";
    public static $cache_authority = "authority";
}

?>