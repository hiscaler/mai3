<?php

namespace app\modules\admin\controllers;

use app\models\Ad;
use app\models\AdSearch;
use app\models\Constant;
use app\models\Option;
use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AdsController implements the CRUD actions for Ad model.
 */
class AdsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'undo', 'toggle'],
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
     * Lists all Ad models.
     * @return mixed
     */
    public function actionIndex($spaceId = null)
    {
        $searchModel = new AdSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams(), $spaceId);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ad model.
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
     * Creates a new Ad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ad();
        $model->loadDefaultValues();
        $model->enabled = Constant::BOOLEAN_TRUE;

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = Yii::$app->getUser()->getId();
        $now = time();
        Yii::$app->getDb()->createCommand()->update('{{%ad}}', [
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
        Yii::$app->getDb()->createCommand()->update('{{%ad}}', [
            'status' => Option::STATUS_PUBLISHED,
            'updated_by' => Yii::$app->getUser()->getId(),
            'updated_at' => time(),
            'deleted_by' => null,
            'deleted_at' => null,
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%ad}} WHERE [[id]] = :id')->bindValue(':id', (int) $id, PDO::PARAM_INT)->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $db->createCommand()->update('{{%ad}}', ['enabled' => $value, 'updated_at' => time()], '[[id]] = :id', [':id' => (int) $id])->execute();
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
     * Finds the Ad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
