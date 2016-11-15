<?php

namespace app\modules\admin\controllers;

use app\models\Constant;
use app\models\Lookup;
use app\models\News;
use app\models\NewsContent;
use app\models\NewsSearch;
use app\models\Yad;
use PDO;
use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 资讯管理
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class NewsController extends GlobalController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'undo', 'toggle', 'toggle-comment', 'remove-image', 'choice-lookup'],
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
                    'toggle-comment' => ['post'],
                    'remove-image' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
//        $metaItems = Meta::getItems($model->className(), $model->id);

        return $this->render('view', [
                'model' => $model,
                'metaItems' => [],
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();
        $model->author = Yii::$app->getUser()->getIdentity()->nickname;
        $model->source = Lookup::getValue('system.models.news.source');
        $model->ordering = News::DEFAULT_ORDERING_VALUE;
        $model->published_at = Yii::$app->getFormatter()->asDateTime(time());

        $newsContent = new NewsContent();

        $post = Yii::$app->getRequest()->post();
        if ($model->load($post) && $newsContent->load($post) && Model::validateMultiple([$model, $newsContent])) {
            $db = Yii::$app->getDb();
            $transaction = $db->beginTransaction();
            try {
                $model->save();
                $model->saveContent($newsContent); // 保存资讯内容
                $model->processPicturePath($model);
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new HttpException(500, $e->getMessage());
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
                    'newsContent' => $newsContent,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $newsContent = $model->newsContent;

        $post = Yii::$app->getRequest()->post();
        if ($model->load($post) && $newsContent->load($post) && Model::validateMultiple([$model, $newsContent])) {
            $model->save(false);
            $newsContent->save(false);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
                    'newsContent' => $newsContent,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = Yii::$app->getUser()->getId();
        $now = time();
        Yii::$app->getDb()->createCommand()->update('{{%news}}', [
            'status' => Constant::STATUS_DELETED,
            'updated_by' => $userId,
            'updated_at' => $now,
            'deleted_by' => $userId,
            'deleted_at' => $now
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    /**
     * Undo Delete an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUndo($id)
    {
        $model = $this->findModel($id);
        Yii::$app->getDb()->createCommand()->update('{{%news}}', [
            'status' => Constant::STATUS_PUBLISHED,
            'updated_by' => Yii::$app->getUser()->getId(),
            'updated_at' => time(),
            'deleted_by' => null,
            'deleted_at' => null,
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    /**
     * Toggle enabled
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%news}} WHERE [[id]] = :id')->bindValue(':id', (int) $id, PDO::PARAM_INT)->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%news}}', ['enabled' => $value, 'updated_by' => Yii::$app->getUser()->getId(), 'updated_at' => $now], '[[id]] = :id', [':id' => (int) $id])->execute();
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
     * Toggle enabled comment function
     * @return Response
     */
    public function actionToggleComment()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled_comment]] FROM {{%news}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([
                ':id' => (int) $id,
                ':tenantId' => Yad::getTenantId()
            ])->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%news}}', ['enabled_comment' => $value, 'updated_by' => Yii::$app->getUser()->getId(), 'updated_at' => $now], 'id = :id', [':id' => (int) $id])->execute();
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
     * 删除案例附件中的截图
     * @param integer $id
     * @return Response
     */
    public function actionRemoveImage($id)
    {
        $db = Yii::$app->getDb();
        $imageSavePath = $db->createCommand('SELECT [[picture_path]] FROM {{%news}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([
                ':id' => (int) $id,
                ':tenantId' => Yad::getTenantId()
            ])->queryScalar();
        if (!empty($imageSavePath)) {
            $db->createCommand()->update('{{%news}}', [
                'picture_path' => null,
                'is_picture_news' => Constant::BOOLEAN_FALSE,
                'updated_by' => Yii::$app->getUser()->getId(),
                'updated_at' => time()
                ], '[[id]] = :id', [':id' => (int) $id])->execute();

            $responseData = [
                'success' => true
            ];
        } else {
            $responseData = [
                'success' => false,
                'error' => [
                    'message' => '数据不存在。'
                ],
            ];
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $responseData,
        ]);
    }

    /**
     * 选择资讯来源和作者
     * @param string $label
     * @return mixed
     */
    public function actionChoiceLookup($label, $current = null)
    {
        if (Yii::$app->getRequest()->isAjax) {
            $values = Lookup::getValue($label);
            $values = $values ? explode(',', $values) : [];

            return $this->renderAjax('choice-lookup', [
                    'values' => $values,
                    'current' => $current,
            ]);
        } else {
            throw new BadRequestHttpException('The requested is bad.');
        }
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = News::find()->where([
                'id' => (int) $id,
                'tenant_id' => Yad::getTenantId(),
            ])->with(['newsContent'])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
