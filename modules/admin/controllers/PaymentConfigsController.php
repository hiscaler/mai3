<?php

namespace app\modules\admin\controllers;

use app\models\PaymentConfig;
use app\models\PaymentConfigSearch;
use app\models\Yad;
use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 支付配置管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class PaymentConfigsController extends ShopController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'toggle'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'toggle' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PaymentConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaymentConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PaymentConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentConfig();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PaymentConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PaymentConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 激活禁止操作
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT [[enabled]] FROM {{%payment_config}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId');
        $command->bindValues([
            ':id' => (int) $id,
            ':tenantId' => Yad::getTenantId(),
        ]);
        $command->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%payment_config}}', ['enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
                    'updatedAt' => Yii::$app->getFormatter()->asDate($now),
                    'updatedBy' => Yii::$app->getUser()->getIdentity()->username,
                ],
            ];
        } else {
            $responseData = [
                'success' => false,
                'error' => [
                    'message' => '数据有误',
                ],
            ];
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $responseData,
        ]);
    }

    /**
     * Finds the PaymentConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
