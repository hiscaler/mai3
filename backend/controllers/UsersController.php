<?php

namespace backend\controllers;

use backend\forms\CreateTenantUserForm;
use backend\forms\RegisterForm;
use common\models\Option;
use common\models\TenantUser;
use common\models\TenantUserSearch;
use common\models\User;
use common\models\Yad;
use PDO;
use yadjet\helpers\ArrayHelper;
use Yii;
use yii\base\Security;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 系统用户管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class UsersController extends GlobalController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'auth', 'create-tenant-user', 'toggle'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'toggle' => ['post'],
                    'delete' => ['post'],
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
        $searchModel = new TenantUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegisterForm();
        $model->type = User::TYPE_BACKEND;
        $model->role = User::ROLE_USER;
        $model->status = User::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = (new Security())->generatePasswordHash($model->password);
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findTenantUserModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ((int) $id == Yii::$app->user->id) {
            throw new BadRequestHttpException("Can't remove itself.");
        }

        $model = $this->findModel($id);
        $userId = $model->id;
        $db = Yii::$app->db;
        $db->transaction(function ($db) use ($userId) {
            $tenantId = Yad::getTenantId();
            $bindValues = [
                ':tenantId' => $tenantId,
                ':userId' => $userId
            ];
            $db->createCommand()->delete('{{%tenant_user}}', '[[tenant_id]] = :tenantId AND [[user_id]] = :userId', $bindValues)->execute();
            $db->createCommand('DELETE FROM {{%auth_node}} WHERE [[user_id]] = :userId AND [[node_id]] IN (SELECT [[id]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId)')->bindValues($bindValues)->execute();
        });

        return $this->redirect(['index']);
    }

    /**
     * 用户节点权限控制
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAuth($id)
    {
        $userId = (int) $id;
        $tenantId = Yad::getTenantId();
        $db = Yii::$app->db;
        $userExists = $db->createCommand('SELECT COUNT(*) FROM {{%user}} WHERE [[id]] = :id AND [[id]] IN (SELECT [[user_id]] FROM {{%tenant_user}} WHERE [[tenant_id]] = :tenantId)')->bindValues([
                ':id' => $userId,
                ':tenantId' => $tenantId
            ])->queryScalar();
        if (!$userExists) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $existingNodeIds = $db->createCommand('SELECT [[node_id]] FROM {{%auth_node}} WHERE [[user_id]] = :userId AND [[node_id]] IN (SELECT [[id]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId)')->bindValues([
                ':userId' => $userId,
                ':tenantId' => $tenantId
            ])->queryColumn();
        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->isAjax) {
            $choiceNodeIds = $request->post('choiceNodeIds');
            if (!empty($choiceNodeIds)) {
                $choiceNodeIds = explode(',', $choiceNodeIds);
                $insertNodeIds = array_diff($choiceNodeIds, $existingNodeIds);
                $deleteNodeIds = array_diff($existingNodeIds, $choiceNodeIds);
            } else {
                $insertNodeIds = [];
                $deleteNodeIds = $existingNodeIds; // 如果没有选择任何节点，表示删除所有已经存在节点
            }

            if ($insertNodeIds || $deleteNodeIds) {
                $transaction = $db->beginTransaction();
                try {
                    if ($insertNodeIds) {
                        $insertRows = [];
                        foreach ($insertNodeIds as $nodeId) {
                            $insertRows[] = [$userId, $nodeId];
                        }
                        if ($insertRows) {
                            $db->createCommand()->batchInsert('{{%auth_node}}', ['user_id', 'node_id'], $insertRows)->execute();
                        }
                    }
                    if ($deleteNodeIds) {
                        $db->createCommand()->delete('{{%auth_node}}', [
                            'user_id' => $userId,
                            'node_id' => $deleteNodeIds
                        ])->execute();
                    }
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return new Response([
                        'format' => Response::FORMAT_JSON,
                        'data' => [
                            'success' => false,
                            'error' => [
                                'message' => $e->getMessage()
                            ]
                        ],
                    ]);
                }
            }

            return new Response([
                'format' => Response::FORMAT_JSON,
                'data' => [
                    'success' => true
                ],
            ]);
        }

        $nodes = $db->createCommand('SELECT [[id]], [[parent_id]] AS [[pId]], [[name]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', $tenantId, PDO::PARAM_INT)->queryAll();
        foreach ($nodes as $key => $node) {
            if ($existingNodeIds && in_array($node['id'], $existingNodeIds)) {
                $nodes[$key]['checked'] = true;
            }
        }
        $nodes = ArrayHelper::toTree($nodes, 'id', 'pId');

        return $this->renderAjax('auth', [
                'nodes' => $nodes,
        ]);
    }

    /**
     * 添加站点管理用户
     * @return mixed
     */
    public function actionCreateTenantUser()
    {
        $model = new CreateTenantUserForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->db->createCommand()->insert('{{%tenant_user}}', [
                'tenant_id' => Yad::getTenantId(),
                'user_id' => $model->user_id,
                'role' => $model->role,
                'rule_id' => $model->rule_id,
                'enabled' => Option::BOOLEAN_TRUE,
                'user_group_id' => $model->user_group_id
            ])->execute();
            Yii::$app->getSession()->setFlash('notice', "用户 {$model->username} 已经成功绑定「" . Yad::getTenantName() . "」站点。");
            return $this->redirect('index');
        }

        return $this->render('createTenantUser', [
                'model' => $model,
        ]);
    }

    /**
     * 切换是否激活开关
     * @return Response
     */
    public function actionToggle()
    {
        $userId = Yii::$app->request->post('id');
        $tenantId = Yad::getTenantId();
        $db = Yii::$app->db;
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%tenant_user}} WHERE [[user_id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([
                ':id' => (int) $userId,
                ':tenantId' => $tenantId
            ])->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%tenant_user}}', ['enabled' => $value], '[[user_id]] = :id AND [[tenant_id]] = :tenantId', [':id' => (int) $userId, ':tenantId' => $tenantId])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value
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

    protected function findTenantUserModel($id)
    {
        $model = TenantUser::find()->where([
                'user_id' => (int) $id,
                'tenant_id' => Yad::getTenantId()
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
