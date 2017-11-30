<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'api',
    'components' => [
        'request' => [
            'class' => 'yii\web\Request',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '%^&*()JH(**)H(JKLG&*(R%^(UJ()J()',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'class' => 'app\modules\api\extensions\Formatter',
            'dateFormat' => 'php:Y-m-j',
            'datetimeFormat' => 'php:Y-m-d h:i:s',
            'nullDisplay' => '',
        ],
        'log' => [
            'class' => 'yii\log\Logger',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                'GET,HEAD api/xxx' => 'api/brand/index',
            ],
        ],
        'i18n' => null,
        'assetManager' => null,
    ],
    'params' => $params,
];


return $config;
