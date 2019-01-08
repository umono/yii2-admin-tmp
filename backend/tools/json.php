<?php

namespace backend\tools;

use yii;
use \yii\web\Response;

/**
 * Trait Json
 * @author liujx
 * @package common\traits
 */
trait json
{
    /**
     * 定义返回json的数据
     * @var array
     */
    protected $arrJson = [
        'code' => 201,
        'msg'  => '',
        'count' => '',
        'data'    => [],
    ];

    /**
     * 响应ajax 返回
     * @param string $array    其他返回参数(默认null)
     * @return mixed|string
     */
    protected function returnJson($array = null)
    {
        // 判断是否覆盖之前的值
        if ($array) $this->arrJson = array_merge($this->arrJson, $array);

        // 没有错误信息使用code 确定错误信息
        if (empty($this->arrJson['msg'])) {
            $errCode = Yii::t('error', 'code');
            $this->arrJson['msg'] = $errCode[$this->arrJson['code']];
        }

        // 设置JSON返回
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->arrJson;
    }

    /**
     * handleJson() 处理返回数据
     * @param mixed $data     返回数据
     * @param integer   $code  返回状态码
     * @param string  $msg   提示信息
     */
    protected function handleJson($data, $code = 0, $msg = '')
    {
        $this->arrJson['code'] = $code;
        $this->arrJson['data'] = $data;
        $this->arrJson['msg'] = $msg;
    }

    /**
     * 处理成功返回
     *
     * @param mixed $data 返回结果信息
     * @return mixed|string
     */
    protected function success($data = [],$count)
    {
        return $this->returnJson([
            'code' => 0,
            'msg' => '获取成功',
            'count' => $count,
            'data' => $data,

        ]);
    }

    /**
     * 处理错误返回
     *
     * @param integer $code 错误码
     * @param string $message
     * @return mixed|string
     */
    protected function error($code = 201, $message = '')
    {
        return $this->returnJson([
            'code' => $code,
            'msg' => $message,
        ]);
    }

    /**
     * 设置错误码
     *
     * @param int $errCode
     */
    public function setCode($errCode = 201)
    {
        $this->arrJson['code'] = $errCode;
    }

    /**
     * 设置错误信息
     *
     * @param string $message
     */
    public function setMessage($message = '')
    {
        $this->arrJson['msg'] = $message;
    }
}