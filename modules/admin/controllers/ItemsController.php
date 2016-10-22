<?php

namespace app\modules\admin\controllers;

use app\models\Constant;
use app\models\Item;
use app\models\ItemSearch;
use app\models\Yad;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ItemsController implements the CRUD actions for Item model.
 */
class ItemsController extends ShopController
{

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'toggle', 'set-default'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'toggle' => ['POST'],
                    'set-default' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
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
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();
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
     * Updates an existing Item model.
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
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $exists = Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%order_detail}} WHERE [[item_id]] = :itemId', [':itemId' => $model['id']])->queryScalar();
        if ($exists) {
            throw new ForbiddenHttpException('该单品已有销售记录，禁止删除。');
        } else {
            $model->delete();
        }

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
        $value = $db->createCommand('SELECT [[online]] FROM {{%item}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId', [
                ':id' => (int) $id,
                ':tenantId' => Yad::getTenantId(),
            ])->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%item}}', ['online' => $value, 'on_off_datetime' => $now, 'enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
                    'updatedAt' => Yii::$app->getFormatter()->asDate($now),
                    'updatedBy' => Yii::$app->getUser()->getIdentity()->username,
                    'onOffDatetime' => Yii::$app->getFormatter()->asDatetime($now),
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
     * 激活禁止操作
     * @return Response
     */
    public function actionSetDefault()
    {
        $id = (int) Yii::$app->getRequest()->post('id');
        $db = Yii::$app->getDb();
        $productId = $db->createCommand('SELECT [[product_id]] FROM {{%item}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId', [':id' => $id, ':tenantId' => Yad::getTenantId()])->queryScalar();
        if ($productId) {
            $now = time();
            $db->createCommand('UPDATE {{%item}} SET [[default]] = :value WHERE id <> :id AND [[product_id]] = :productId', [':value' => Constant::BOOLEAN_FALSE, ':id' => $id, ':productId' => $productId])->execute();
            $db->createCommand()->update('{{%item}}', ['default' => Constant::BOOLEAN_TRUE, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => Constant::BOOLEAN_TRUE,
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
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id) ) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
