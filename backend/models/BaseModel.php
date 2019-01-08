<?php

namespace backend\models;


use api\models\PageParam;
use backend\modules\system\models\Admin;
use backend\modules\system\models\AdminLog;
use common\tools\Upload;
use function GuzzleHttp\Psr7\uri_for;
use \yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use Yii;

/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/7
 * Time: 下午5:03
 */
class BaseModel extends  ActiveRecord
{

    public function model()
    {
        return self::name();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
//        $admin = new Admin();
//        if($admin->is_password){return true;}
//        var_dump($insert);die;
        if($insert) {
            $data = $this->attributes;
            $index = Yii::$app->request->post();
            AdminLog::create(AdminLog::TYPE_CREATE,$data,$index);
        } else {
            $data = $this->attributes;
            $index = Yii::$app->request->post();
            AdminLog::create(AdminLog::TYPE_UPDATE,$data,$index);
        }
    }

    /**
     * 删除操作必须是管理员以上级别
     * @return bool
     */
    public function beforeDelete()
    {
        parent::beforeDelete(); // TODO: Change the autogenerated stub
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        foreach ($role as $k => $v){
            if(($k == 'admin' || $k == 'administrator')){
                return true;
            }else{
                $this->addError('username', '暂无权限');
                return false;
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $index = Yii::$app->request->post('id');
        $data = Yii::$app->request->post();
        AdminLog::create(AdminLog::TYPE_DELETE,$data,$index);
    }


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
    public static function getModelError($model) {
        $errors = $model->getErrors();    //得到所有的错误信息
        if(!is_array($errors)){
            return '';
        }
        $firstError = array_shift($errors);
        if(!is_array($firstError)){
            return '';
        }
        return array_shift($firstError);
    }

}
