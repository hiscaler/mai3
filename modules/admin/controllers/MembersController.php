<?php

namespace app\modules\admin\controllers;

use app\models\MemberSearch;
use app\models\User;
use app\models\Yad;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 会员管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class MembersController extends GlobalController
{

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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember(Yii::$app->getRequest()->getUrl());
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = User::find()->where([
                'id' => (int) $id,
            ])
            ->andWhere('[[id]] IN (SELECT [[user_id]] FROM {{%tenant_user}} WHERE [[tenant_id]] = :tenantId)', [':tenantId' => Yad::getTenantId()])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
