<?php
return [
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN')??'mysql:host=localhost;dbname=yii2',
            'username' => getenv('DB_USERNAME')??'root',
            'password' => getenv('DB_PASSWORD')??'root',
            'charset' => 'utf8',
            'tablePrefix' => getenv('DB_TABLE_PREFIX')??null,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'oid' => [
            'class' => 'common\tools\OrderId',
        ],
        'const' => [
            'class' => 'common\components\Constant',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',  //每种邮箱的host配置不一样
                'username' => 'admin@noetic.me',//邮箱账号
                'password' => 'ukkzroktnemhbeba',//邮箱密码（填授权码！！！）
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['admin@noetic.me'=>'**鲜花商城']
            ],
        ],
    ],
];
