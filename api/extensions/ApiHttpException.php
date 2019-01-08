<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/12
 * Time: 下午3:38
 */

namespace api\extensions;

use yii\web\HttpException;

class ApiHttpException extends HttpException
{
    public function __construct($status, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->statusCode = $status;
        parent::__construct($status, $message, $code, $previous);
    }
}