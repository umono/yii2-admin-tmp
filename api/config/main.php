<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => '****APP',
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'user_credentials' => 'api\modules\v1\models\user\Member',
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ]
            ]
        ]
    ],
    'controllerNamespace' => 'api\controllers',
    'components' => [
       'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',  //每种邮箱的host配置不一样
                'username' => 'admin@noetic.me',//邮箱账号
                'password' => 'xapfjotkumjjbech',//邮箱密码（填授权码！！！）
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['admin@noetic.me'=>'****']
            ],
        ],
       'response' => [
           'class' => 'yii\web\Response',
           'on beforeSend' => function ($event) {
               $response = $event->sender;
               if ($response->data !== null){
                   if ($response->statusCode == '200'){
                       $response->data = [
                           'success' => $response->isSuccessful,
                           'data' =>$response->data,
                           'Encrypt' =>\common\tools\AesEncrypt::aes_encrypt((json_encode($response->data))),
                           'status' => $response->statusCode,
                       ];
                       $response->statusCode = 200;
                   }else{
                       $response->data = [
                           'success' => $response->isSuccessful,
                           'data' =>$response->data,
                           'status' => $response->statusCode,
                       ];
                       $response->statusCode = 200;
                   }
               }
           },
       ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'user' => [
            'identityClass' => 'api\modules\v1\models\user\Member',
            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'v1/default/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                //登录注册
                'POST <action:\w+>' => 'v1/default/<action>',
                'POST reset-password' => 'v1/default/reset-password',
                'GET reset-password' => 'v1/default/reset-password',
                'GET v1/user' => 'v1/user/index',
                //令牌授权
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',
                //图片上传
                // 'GET upload/<action:\w+>' => 'upload/<action>',
                'POST upload/<action:\w+>' => 'upload/<action>',
                /**---------------------------------------------
                 * 测试
                 * ---------------------------------------------*/
                'GET test/<action:\w+>' => 'test/<action>',
                'GET testoauth/<action:\w+>' => 'testoauth/<action>',
                'POST testoauth/<action:\w+>' => 'testoauth/<action>',
                'GET oauth/<action:\w+>' => 'oauth/<action>',
                //
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/user'],
                    'extraPatterns' => [
                        'POST logout' => 'logout',
                        'POST reset-pwd' => 'reset-pwd',
                    ], 'pluralize' => false]
            ],
        ],
    ],
    'params' => $params,
];
