<?php
namespace Admin\Widget;

use Think\Controller;

class HeaderWidget extends \Admin\Controller\BaseController
{
    public function header()
    {
        $this->display("Widget:header");
    }


}

?>