<?php
namespace Admin\Model;

use Admin\Common\CommonJson;
use Admin\Common\Config;
use Admin\Common\Util;
use Think\Model;

/**
 * 新闻模型
 * @package Admin\Model
 */
class NewsModel extends Model
{

    /**
     * @var array
     */
    protected $_validate = array(
        array('title', 'require', '新闻名称不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,100', '新闻名称不能不能超过20个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('title', '', '新闻名称已经存在！', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('type_id', '-1', '请选择新闻类别！', self::MUST_VALIDATE, 'notequal', self::MODEL_BOTH),
    );


    public function doAdd($type_name, $user_name)
    {

        $this->startTrans();
        try {
            //region 这里只保存第一张图片,作为LOGO使用
            if (!empty($this->image)) {
                $tempimageString = str_ireplace(Config::$uploadConfig["lookPath"], "", $this->image);
                $imageList = explode(",", $tempimageString);
                if (count($imageList) > 0) {
                    $this->image = $imageList[0];
                }
            }
            //endregion

            //region 保存新闻
            $this->type_name = $type_name;
            $this->create_time = Util::now();
            $this->update_time = Util::now();
            $this->user_name = $user_name;
            $id = $this->add();
            //endregion

            //region 图片保存到news_image表
            foreach ($imageList as $image) {
                $newsImage = D("NewsImage");
                $newsImage->news_id = $id;
                $newsImage->path = $image;
                $newsImage->create_time = Util::now();
                $newsImage->add();

            }
            //endregion

            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }

    public function doEdit($type_name, $user_name)
    {
        $this->startTrans();
        try {
            //region 这里只保存第一张图片,作为LOGO使用
            if (!empty($this->image)) {
                $tempimageString = str_ireplace(Config::$uploadConfig["lookPath"], "", $this->image);
                $imageList = explode(",", $tempimageString);
                if (count($imageList) > 0) {
                    $this->image = $imageList[0];
                }
            }
            //endregion

            //region 保存新闻
            $news_id = $this->id;
            $this->type_name = $type_name;
            $this->update_time = Util::now();
            $this->user_name = $user_name;
            $id = $this->save();
            //endregion

            //region 图片保存到news_image表
            $newsImage = D("NewsImage");
            //之前的全部删除掉,重新添加一遍
            $state = $newsImage->where("news_id=$news_id")->delete();
            foreach ($imageList as $image) {

                $newsImage->news_id = $news_id;
                $newsImage->path = $image;
                $newsImage->create_time = Util::now();
                $newsImage->add();

            }
            //endregion

            $this->commit();
            return true;

        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }

}
