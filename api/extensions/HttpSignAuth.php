<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/12
 * Time: 下午3:39
 */

namespace api\extensions;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class HttpSignAuth extends Behavior
{
    public $privateKey = '12345678';

    public $signParam = 'sign';

    public function events() {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    public function beforeAction($event) {
        //获取 sign
        $sign = Yii::$app->request->get($this->signParam, null);
        $getParams = Yii::$app->request->get();
        $postParams = Yii::$app->request->post();
        $params = array_merge($getParams, $postParams);
        if(empty($sign) || !$this->checkSign($sign, $params)){
            $error = ErrorCode::getError('auth_error');
            throw new ApiHttpException($error['status'], $error['msg'], $error['code']);
        }
        return true;
    }

    private function checkSign($sign, $params) {
        unset($params[$this->signParam]);
        ksort($params);
        return md5($this->privateKey . implode(',', $params)) === $sign;
    }

}