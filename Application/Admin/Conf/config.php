<?php
return array(

    //region 模板相关配置,通过异常模板没加载出来得出结论,这个配置的加载是在控制器的加载之后执行的
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/Static',
        '__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/img',
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__FONT__' => __ROOT__ . '/Public/' . MODULE_NAME . '/font',
        '__PLUGIN__' => __ROOT__ . '/Public/' . MODULE_NAME . '/plugin',
        '__TITLE__' => "网站标题",
        '__KEYWORD__' => "网站关键字",
        '__DESCRIPTION__' => "网站描述",
        '__PICTURE__' => __ROOT__ . '/Uploads/Picture',
    ),


    //region 数据库配置
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '192.168.1.13', // 服务器地址
    'DB_NAME' => 'd84398c0976f34882945bfb0060e4c3cc', // 数据库名
    'DB_USER' => '3172528a-11c9', // 用户名
    'DB_PWD' => '67bbe0c8-36f0', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'or_', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 字符集
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    //endregion

    //region SESSION 和 COOKIE 配置
    'SESSION_PREFIX' => 'or_admin_', //session前缀
    'COOKIE_PREFIX' => 'or_admin_', // Cookie前缀 避免冲突
    //endregion




);