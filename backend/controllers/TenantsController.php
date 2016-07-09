<?php

namespace backend\controllers;

use backend\modules\admin\forms\CreateTenantUserForm;
use common\models\Option;
use common\models\Tenant;
use common\models\TenantSearch;
use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 站点管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class TenantsController extends GlobalController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'undo', 'toggle', 'create-user'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'undo' => ['post'],
                    'toggle' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tenant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TenantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tenant model.
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
     * Creates a new Tenant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tenant();
        $model->date_format = 'php:Y-m-d';
        $model->time_format = 'php:H:i:s';
        $model->datetime_format = 'php:Y-m-d H:i:s';
        $model->status = Option::BOOLEAN_TRUE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tenant model.
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
     * Deletes an existing Tenant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = Yii::$app->user->id;
        $now = time();
        Yii::$app->db->createCommand()->update('{{%tenant}}', [
            'status' => Option::STATUS_DELETED,
            'updated_by' => $userId,
            'updated_at' => $now,
            'deleted_by' => $userId,
            'deleted_at' => $now
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    /**
     * Undo delete an existing Special model.
     * If undo delete is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUndo($id)
    {
        $model = $this->findModel($id);
        Yii::$app->db->createCommand()->update('{{%tenant}}', [
            'status' => Option::STATUS_PUBLISHED,
            'updated_by' => Yii::$app->user->id,
            'updated_at' => time(),
            'deleted_by' => null,
            'deleted_at' => null,
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    /**
     * 切换记录禁止、激活状态
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->getRequest()->post('id');
        $db = Yii::$app->db;
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%tenant}} WHERE [[id]] = :id')->bindValue(':id', (int) $id, PDO::PARAM_INT)->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $db->createCommand()->update('{{%tenant}}', ['enabled' => $value, 'updated_at' => time(), 'deleted_by' => null, 'deleted_at' => null], '[[id]] = :id', [':id' => (int) $id], PDO::PARAM_INT)->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
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
     * 添加站点管理用户
     * @return mixed
     */
    public function actionCreateUser($id)
    {
        $tenant = $this->findModel($id);
        $model = new CreateTenantUserForm();
        $model->tenant_id = $tenant['id'];
        $model->tenant_name = $tenant['name'];

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            Yii::$app->db->createCommand()->insert('{{%tenant_user}}', [
                'tenant_id' => $tenant->id,
                'user_id' => $model->user_id,
                'user_group_id' => $model->user_group_id,
                'role' => $model->role,
                'rule_id' => $model->rule_id,
                'enabled' => Option::BOOLEAN_TRUE
            ])->execute();
            Yii::$app->getSession()->setFlash('notice', "用户 {$model->username} 已经成功绑定「{$tenant->name}」站点。");
            return $this->redirect(['view', 'id' => $tenant->id, 'tab' => 'users']);
        }

        return $this->render('createUser', [
                'tenant' => $tenant,
                'model' => $model,
        ]);
    }

    /**
     * Finds the Tenant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tenant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tenant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
