<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::$app->setComponents([
            'errorHandler' => [
                'class' => 'yii\web\ErrorHandler',
                'errorAction' => '/admin/default/error',
            ],
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'app\models\User',
                'enableAutoLogin' => true,
                'loginUrl' => 'login'
            ],
        ]);
    }

}
