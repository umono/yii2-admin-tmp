<?php

namespace  api\models;


use common\tools\Upload;
use Yii;
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/14
 * Time: 下午10:33
 */
class BaseModel extends \yii\db\ActiveRecord
{

    public function __construct()
    {
        return get_class($this);
    }


    static function name()
    {
        return get_called_class();
    }

    //判断类是否为真
    public function getModelNameFormType($type)
    {
        if ($type == self::name()) {
            return true;
        }else{
            return false;
        }

    }

    //获取图片url
    public function getPhoto($id)
    {
        $item = Upload::find()->select(['url'])->where('type_id=:type_id',[':type_id'=>$id])
            ->andWhere('type_model=:type_model',[':type_model'=>self::name()])
            ->andWhere('user_id=:user_id',[':user_id'=>Yii::$app->user->id])
            ->asArray()
            ->all();
        if (empty($item)){
        }
        return $item;
    }


    //分页模型
    public function Page()
    {
        $page = new PageParam();
        return $page;
    }
}