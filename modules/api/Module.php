<?php

namespace app\modules\api;

use Yii;

/**
 * api module definition class
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
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
        Yii::configure($this, require(__DIR__ . '/config/main.php'));

        Yii::$app->setComponents([
            'formatter' => [
                'class' => 'app\modules\api\extensions\Formatter',
                'dateFormat' => 'php:Y-m-d',
                'datetimeFormat' => 'php:Y-m-d h:i:s',
                'nullDisplay' => '',
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
            'i18n' => null,
            'assetManager' => null,
        ]);

        \Yii::$app->getErrorHandler()->errorAction = 'api/default/error';
    }

    public function bootstrap($app)
    {
        \Yii::$app->urlManager->addRules(require(__DIR__ . '/config/rules.php'), false);
    }

}
