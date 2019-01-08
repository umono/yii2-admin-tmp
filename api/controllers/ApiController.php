<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/12
 * Time: 下午2:10
 */

namespace api\controllers;

use api\modules\v1\models\user\Member;
use common\tools\AesEncrypt;
use yii\rest\ActiveController;
use Yii;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\HttpBearerAuth;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;

class ApiController  extends ActiveController
{
    public $param;
    public $header;
    public $user_id;
    public $get;
    public $openid;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions);
    }
//    public function behaviors()
//    {
//        return ArrayHelper::merge(parent::behaviors(), [
//            'authenticator' => [
//                'class' => CompositeAuth::className(),
//                'authMethods' => [
//                    ['class' => HttpBearerAuth::className()],
//                    ['class' => QueryParamAuth::className(),
//                        'tokenParam' => 'accessToken',
//                    ],
//                ],
//            ],
//            'corsFilter'  => [
//                'class' => Cors::className(),
//                'cors' => [
//                    // restrict access to
//                    'Origin' => ['*'],
//                    // restrict access to
//                    'Access-Control-Request-Method' => ['*'],
//                    // Allow only POST and PUT methods
//                    'Access-Control-Request-Headers' => ['*'],
//                    // Allow only headers 'X-Wsse'
//                    'Access-Control-Allow-Credentials' => true,
//                    // Allow OPTIONS caching
//                    'Access-Control-Max-Age' => 3600,
//                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
//                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
//                ],
//            ],
//            'exceptionFilter' => [
//                'class' => ErrorToExceptionFilter::className()
//            ],
//        ]);
//    }
    //初始化
    public function init()
    {
        $this->header = Yii::$app->request->headers;
        $this->get = Yii::$app->request->get();
        if (!Yii::$app->request->isGet) {
            $this->param = Yii::$app->request->getBodyParams();
            if (!empty($this->param['body'])) {
                $this->param = str_replace(' ', "+", $this->param['body']);
                $this->param = json_decode(AesEncrypt::aes_decrypt($this->param), true);
            }
        }
        $token = $this->header['Authorization'];
        if (!empty($token)) {
            $_token = substr($token, 7);
            $token_me = Member::find()->where(['=', 'access_token', $_token])->asArray()->one();
            if (!empty($token_me)) {
                $this->user_id = $token_me['id'];
                $this->openid = $token_me['openId'];
            } else {
            }
        } else {
            $this->user_id = 0;
            $this->openid = '';
        }
        if ($this->user_id == 0) {
            if ($this->module->module->requestedRoute != 'v1/group/share-info') {
                throw new UnauthorizedHttpException("登录失效,请重新登录");
            }
        }
    }
    /**
     * 返回模型错误信息
     * @param $model
     * @return mixed|string
     */
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