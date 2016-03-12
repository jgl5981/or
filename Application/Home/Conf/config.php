<?php
return array(
	//'配置项'=>'配置值'
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/Static',
        '__ADDONS__' => __ROOT__ . '/Public/'. MOULE_NAME . '/Addons',
        '__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/img',
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__DEMO__' => __ROOT__ . '/Public/' . MODULE_NAME . '/demo',
        '__LAYER__' => __ROOT__ . '/Public/' . MODULE_NAME . '/layer',
        '__LAYDATE__' => __ROOT__ . '/Public/' . MODULE_NAME . '/laydate'
    ),
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',
);