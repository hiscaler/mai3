<?php

namespace app\modules\admin\controllers;

use app\models\Specification;
use app\models\Type;
use app\models\TypeProperty;
use app\models\TypeSearch;
use app\models\Yad;
use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 商品类型管理
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class TypesController extends ShopController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'create-property', 'toggle'],
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
     * Lists all Type models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Type model.
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
     * Creates a new Type model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Type();
        $model->loadDefaultValues();
        $model->brandIdList = $model->specificationIdList = [];
        $specifications = Specification::findAll(['tenant_id' => Yad::getTenantId()]);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
                    'specifications' => $specifications,
            ]);
        }
    }

    /**
     * Updates an existing Type model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $db = Yii::$app->getDb();
        $model->brandIdList = $db->createCommand('SELECT brand_id FROM {{%type_brand}} WHERE [[type_id]] = :typeId')->bindValue(':typeId', $model->id)->queryColumn();
        $specifications = Specification::findAll(['tenant_id' => Yad::getTenantId()]);
        $model->specificationIdList = $db->createCommand('SELECT specification_id FROM {{%type_specification}} WHERE [[type_id]] = :typeId')->bindValue(':typeId', $model->id)->queryColumn();

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
                    'specifications' => $specifications,
            ]);
        }
    }

    /**
     * Deletes an existing Type model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $exists = Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%product}} WHERE [[type_id]] = :type', [':type' => $model['id']])->queryScalar();
        if ($exists) {
            throw new ForbiddenHttpException('该商品类型已有商品使用，禁止删除。');
        } else {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * 添加商品属性
     * @param integer $typeId
     * @return mixed
     */
    public function actionCreateProperty($typeId)
    {
        $this->layout = 'ajax';
        $type = $this->findModel($typeId);
        $model = new TypeProperty();
        $model->type_id = $type['id'];

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create-property', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * 激活禁止操作
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->getRequest()->post('id');
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT [[enabled]] FROM {{%type}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId');
        $command->bindValues([
            ':id' => (int) $id,
            ':tenantId' => Yad::getTenantId(),
        ]);
        $command->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%type}}', ['enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
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
     * Finds the Type model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Type the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Type::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The getRequest()ed page does not exist.');
        }
    }

}
