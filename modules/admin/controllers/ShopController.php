<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;

/**
 * 店铺管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ShopController extends Controller
{

    public $layout = 'shop';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 店铺管理
     * @return mixed
     */
    public function actionIndex()
    {
        $db = Yii::$app->getDb();
        $members = $db->createCommand('SELECT [[id]], [[username]], [[avatar]], [[email]], [[created_at]] FROM {{%user}} WHERE [[type]] = :type ORDER BY [[id]] DESC', [':type' => \app\models\User::TYPE_MEMBER])->queryAll();
        $orders = $db->createCommand('SELECT [[id]], [[sn]], [[total_amount]], [[created_at]] FROM {{%order}} ORDER BY [[id]] DESC')->queryAll();

        return $this->render('index', [
                    'members' => $members,
                    'orders' => $orders,
        ]);
    }

}
