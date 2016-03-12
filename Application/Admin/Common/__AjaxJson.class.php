<?php

namespace Admin\Common;


/**
 * Class AjaxJson-参考
 * Ajax请求返回Json对象
 * @package Admin\Common
 */
class AjaxJson
{

    /**
     * 状态代码,-1/0/1/2,异常/失败/成功/重定向
     */
    public $code;
    /**
     * 显示信息
     */
    public $message;
    /**
     * 重定向地址，只有在code=2时，才生效
     */
    public $redirect;
    /**
     * 传输数据，回调函数需要使用的参数
     */
    public $data;
    /**
     * 客户端需要回调的函数，函数入参data,AjaxJson,xhr
     */
    public $callback;
    /**
     *错误信息，只有在code=-1时，才显示该信息
     */
    public $error;

}

?>