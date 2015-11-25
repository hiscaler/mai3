<?php

namespace backend\controllers;

use common\models\Specification;
use common\models\SpecificationSearch;
use common\models\Yad;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 商品规格管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class SpecificationsController extends Controller
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
     * Lists all Specification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpecificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Specification model.
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
     * Creates a new Specification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Specification();
        $model->valuesData = $model->values;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Specification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->valuesData = $model->values;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Specification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteValue($id)
    {
        $success = false;
        $errorMessage = null;
        $db = Yii::$app->getDb();
        $specificationId = $db->createCommand('SELECT [[specification_id]] FROM {{%specification_value}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([':id' => (int) $id, ':tenantId' => Yad::getTenantId()])->queryScalar();
        if ($specificationId) {
            $exists = $db->createCommand('SELECT COUNT(*) FROM {{%item_type_specification}} WHERE [[specification_id]] = :specificationId')->bindValue(':specificationId', $specificationId)->queryScalar();
            if ($exists) {
                $errorMessage = '商品类型管理中使用了该规格，禁止删除。';
            } else {
                $db->createCommand()->delete('{{%specification_value}}', ['id' => (int) $id])->execute();
                $success = true;
            }
        } else {
            $errorMessage = '记录不存在。';
        }

        $responseBody = ['success' => $success];
        if (!$success) {
            $responseBody['error']['message'] = $errorMessage;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $responseBody
        ]);
    }

    /**
     * Finds the Specification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Specification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Specification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
