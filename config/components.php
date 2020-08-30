<?php

use app\models\AuthorDataProviderBuilder;
use app\models\PostDataProviderBuilder;

$db = require __DIR__ . '/db.php';

return [
    'request' => [
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => 'XLLf445lb_4pui-z_Kx0VbMfnRQnz0Sq',
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'user' => [
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => true,
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
        'useFileTransport' => true,
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
    'db' => $db,
    PostDataProviderBuilder::class => function () {
        return new PostDataProviderBuilder(Yii::$app->db);
    },
    AuthorDataProviderBuilder::class => function () {
        return new AuthorDataProviderBuilder(Yii::$app->db);
    },
    /*
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
        ],
    ],
    */
];
