<?php


namespace api\extensions;

class ErrorCode
{
    private static $error = [
        'system_error'  => [
            'status'    => 500,
            'code'      => 500000,
            'msg'       => 'system error',
        ],
        'auth_error'    => [
            'status'=> 401,
            'code'  => 400000,
            'msg'   => 'auth error',
        ],
        'params_error'  => [
            'status'=> 401,
            'code'  => 400001,
            'msg'   => 'params error',
        ],
    ];

    private function __construct(){

    }

    public static function getError($key){
        if(empty($key) || !isset(self::$error[$key])){
            throw new \Exception("error code not exist", 400);
        }
        return self::$error[$key];
    }

}