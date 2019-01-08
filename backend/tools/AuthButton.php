<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/10
 * Time: 下午10:33
 */

namespace backend\tools;


use backend\modules\system\models\Admin;
use Yii;

class AuthButton
{

    public static function is_auth()
    {
        //是否为管理级别以上的用户。
        $id = Yii::$app->user->id;
        $me = Yii::$app->authManager->getRolesByUser($id);
        if ($id == Admin::SUPER_ADMIN_ID){
            return true;
        }

        foreach ($me as $k => $v) {
            if($k == 'admin' || $k == 'administrator'){
                return true;
            }
        }
//        if (Yii::$app->user->can(self::getAction())){
//            return true;
//        }

        return false;
    }

//    public static function getAction()
//    {
//        $action = Yii::$app->controller->module->id .'/'.Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
//
//        return $action;
//    }
}