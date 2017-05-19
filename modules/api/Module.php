<?php

namespace app\modules\api;

use Yii;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->setComponents([
            'formatter' => [
                'class' => 'app\modules\api\extensions\Formatter',
                'dateFormat' => 'php:Y-n-j',
                'datetimeFormat' => 'php:Y-n-j h:i:s',
                'nullDisplay' => '',
            ],
            'urlManager' => [
                'class' => 'yii\web\urlManager',
                'enablePrettyUrl' => true,
                'enableStrictParsing' => false,
                'showScriptName' => true,
                'rules' => [
                    'class' => 'yii\rest\UrlRule',
                    // Client
                    'GET,HEAD brands' => 'brand/index',
                    'GET,HEAD brands/list' => 'brand/list',
                    'GET,HEAD brands/<id:\d+>' => 'brand/view',
                ],
            ],
            'request' => [
                'class' => '\yii\web\Request',
                'enableCookieValidation' => false,
                'parsers' => [
                    'application/json' => 'yii\web\JsonParser',
                ],
            ],
            'response' => [
                'class' => 'yii\web\Response',
                'formatters' => [
                    \yii\web\Response::FORMAT_JSON => [
                        'class' => 'yii\web\JsonResponseFormatter',
                        'encodeOptions' => JSON_NUMERIC_CHECK + JSON_UNESCAPED_UNICODE,
                        'prettyPrint' => YII_DEBUG,
                    ],
                ],
                'on beforeSend' => function ($event) {
                    $response = $event->sender;
                    if ($response->data !== null && $response->isSuccessful) {
                        $response->data = [
                            'success' => true,
                            'data' => $response->data,
                        ];
                        $response->statusCode = 200;
                    } else {
                        $response->data = [
                            'success' => false,
                            'error' => $response->data,
                        ];
                    }
                },
            ],
            'errorHandler' => [
                'class' => '\yii\web\errorHandler',
                'errorAction' => 'default/error',
            ],
        ]);
    }

}
