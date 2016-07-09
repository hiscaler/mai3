<?php

namespace backend\controllers;

use common\models\Label;
use common\models\LabelSearch;
use common\models\Option;
use common\models\Yad;
use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 推送位管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class LabelsController extends GlobalController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'toggle', 'toggle-entity-enabled'],
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
                    'toggle-entity-enabled' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Label models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LabelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Label model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Label();
        $model->status = Option::BOOLEAN_TRUE;
        $model->ordering = Label::DEFAULT_ORDERING_VALUE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Label model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Label model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->frequency) {
            throw new NotAcceptableHttpException('The requested does not acceptable.');
        } else {
            Yii::$app->db->createCommand()->update('{{%attribute}}', [
                'deleted_at' => time(),
                'deleted_by' => Yii::$app->user->id,
                'enabled' => Option::BOOLEAN_FALSE
                ], '[[id]] = :id', [':id' => $model->id])->execute();
            return $this->redirect(['index']);
        }
    }

    /**
     * 激活禁止操作
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT [[enabled]] FROM {{%attribute}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId');
        $command->bindValues([
            ':id' => (int) $id,
            ':tenantId' => Yad::getTenantId(),
        ]);
        $command->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%attribute}}', ['enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->user->id, 'deleted_at' => null, 'deleted_by' => null], '[[id]] = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
                    'updatedAt' => Yii::$app->formatter->asDate($now),
                    'updatedBy' => Yii::$app->user->getIdentity()->username,
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
     * 关联的实体数据激活禁止操作
     * @return Response
     */
    public function actionToggleEntityEnabled()
    {
        $id = Yii::$app->request->post('id');
        $connection = Yii::$app->db;
        $command = $connection->createCommand('SELECT [[entity_enabled]] FROM {{%attribute}} WHERE [[id]] = :id');
        $command->bindValue(':id', (int) $id);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $connection->createCommand()->update('{{%attribute}}', ['entity_enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->user->id], 'id = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
                    'updatedAt' => Yii::$app->formatter->asDate($now),
                    'updatedBy' => Yii::$app->user->getIdentity()->username,
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
     * Finds the Label model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Label the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Label::find()->where([
                'id' => (int) $id,
                'tenant_id' => Yad::getTenantId(),
            ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
