<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/12
 * Time: 下午1:30
 */

namespace api\modules\v1\controllers;



use api\controllers\ApiController;
use api\modules\v1\models\user\Member;
use api\modules\v1\models\user\ResetPwd;
use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;


use yii\filters\auth\HttpBearerAuth;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends ApiController
{
    public $modelClass = 'api\modules\v1\models\user\Member';


    public function actions()
    {
        $actions = parent::actions();

        // 禁用"delete" 和 "create" \"index" 动作
        unset($actions['delete'], $actions['create'],$actions['index'],$actions['update']);

        return $actions;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::className()],
                    ['class' => QueryParamAuth::className(),
                        'tokenParam' => 'accessToken',
                    ],
                ],
            ],
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    /**
     * 用户登出
     * @return mixed
     */
    public function actionLogout()
    {
        $user = new Member();
        return $user->logout();
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $param = Yii::$app->getRequest()->getBodyParams();
        if (isset($param['username'])||isset($param['password']) || isset($param['status']) || isset($param['member_type'])){
            throw new BadRequestHttpException('请求参数有误');
        }
        $model->load($param, '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    public function actionResetPwd()
    {
        $model = new ResetPwd();
        $pwd = Yii::$app->request->post('pwd');
        $password = Yii::$app->request->post('password');
        $model->pwd = $pwd;
        $model->password = $password;
        if ($model->validate() && $model->resetPassword()) {
            return '密码修改成功。';
        }else{
            throw new UnauthorizedHttpException('旧密码输入有误！');
        }

    }

    public function actionIndex()
    {
        return $this->findModel(Yii::$app->user->id);
    }

//    // 用户信息数据只能是当前用户
//     public function checkAccess($action, $model = null, $params = [])
//     {
//         $oauthUser = Yii::$app->user->identity;
//         $uid = Yii::$app->request->get('id');
//
//         if ($oauthUser['id'] != $uid) {
//             throw new UnauthorizedHttpException('当前访问无权限。');
//         }else{
//             return true;
//         }
//
//     }
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}