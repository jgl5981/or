<?php

namespace Think\Template\TagLib;
use Think\Template\TagLib;


class Test extends TagLib
{

    protected $tags = array(
        // 定义标签
        'input' => array('attr' => 'type,name,id,value', 'close' => 0), // input标签
        'textarea' => array('attr' => 'name,id'),
    );

    // input标签解析
    public function _input($tag,$content)   {
        $name   =   $tag['name'];
        $id    =    $tag['id'];
        $type   =   $tag['type'];
        $value   =   $this->autoBuildVar($tag['value']);
        $str = "<input type='".$type."' id='".$id."' name='".$name."' value='".$value."' />";
        return $str;
    }

    // textarea标签解析
    public function _textarea($tag,$content)   {
        $name  =   $tag['name'];
        $id    =   $tag['id'];
        $str   =   '<textarea id="'.$id.'" name="'.$name.'">'.$content.'</textarea>';
        return $str;
    }
}

?>