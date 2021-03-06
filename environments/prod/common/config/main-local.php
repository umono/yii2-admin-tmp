<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN')??'mysql:host=localhost;dbname=yii2',
            'username' => getenv('DB_USERNAME')??'root',
            'password' => getenv('DB_PASSWORD')??'root',
            'charset' => 'utf8',
            'tablePrefix' => getenv('DB_TABLE_PREFIX')??null,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
