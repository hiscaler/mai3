<?php

namespace backend\controllers;

use common\models\ItemType;
use common\models\ItemTypeSearch;
use common\models\Specification;
use common\models\Yad;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * 商品类型管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ItemTypesController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ItemType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItemType model.
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
     * Creates a new ItemType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItemType();
        $model->brandIdList = $model->specificationIdList = [];
        $specifications = Specification::findAll(['tenant_id' => Yad::getTenantId()]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
                    'specifications' => $specifications,
            ]);
        }
    }

    /**
     * Updates an existing ItemType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $db = Yii::$app->getDb();
        $model->brandIdList = $db->createCommand('SELECT brand_id FROM {{%item_type_brand}} WHERE [[item_type_id]] = :itemTypeId')->bindValue(':itemTypeId', $model->id)->queryColumn();
        $specifications = Specification::findAll(['tenant_id' => Yad::getTenantId()]);
        $model->specificationIdList = $db->createCommand('SELECT specification_id FROM {{%item_type_specification}} WHERE [[item_type_id]] = :itemTypeId')->bindValue(':itemTypeId', $model->id)->queryColumn();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
                    'specifications' => $specifications,
            ]);
        }
    }

    /**
     * Deletes an existing ItemType model.
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
     * Finds the ItemType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ItemType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ItemType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
