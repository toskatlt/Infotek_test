<?php

$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'book/index',
    'bootstrap' => [
        'log',
        'gii',
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module'
        ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'advanced-backend',
            'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 24 * 30], // 30 дней
            'timeout' => 3600 * 24 * 30, // 30 дней
            'useCookies' => true,
        ],
        'request' => [
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'REBiMTx8foEjlc_ObMUxJwnt2pmwGfs-',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => $db,
    ]
];

return $config;
