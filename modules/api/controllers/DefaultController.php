<?php

namespace app\modules\api\controllers;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{

    public $layout = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

}
