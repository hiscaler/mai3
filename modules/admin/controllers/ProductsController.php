<?php

namespace app\modules\admin\controllers;

use app\models\Meta;
use app\models\Product;
use app\models\ProductSearch;
use app\models\Specification;
use app\models\Type;
use app\modules\admin\components\DynamicMetaModel;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 商品管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ProductsController extends ShopController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'delete-image', 'type-raw-data', 'update-image-description'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->loadDefaultValues();
        $metaItems = Meta::getItems($model);
        $dynamicModel = DynamicMetaModel::make($metaItems);

        if ($model->load(Yii::$app->getRequest()->post()) && $dynamicModel->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->save();
            Meta::saveValues($model, $dynamicModel); // 保存 Meta 数据
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
                    'metaItems' => $metaItems,
                    'dynamicModel' => $dynamicModel,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $specifications = Specification::getList(true);
        $metaItems = Meta::getItems($model);
        $dynamicModel = DynamicMetaModel::make($metaItems, false);

        if ($model->load(Yii::$app->getRequest()->post()) && $dynamicModel->load(Yii::$app->getRequest()->post()) && $model->save()) {
            Meta::saveValues($model, $dynamicModel);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
                    'specifications' => $specifications,
                    'metaItems' => $metaItems,
                    'dynamicModel' => $dynamicModel,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $exists = Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%item}} WHERE [[product_id]] = :productId', [':productId' => $model['id']])->queryScalar();
        if ($exists) {
            throw new ForbiddenHttpException('该商品已有单品使用，禁止删除。');
        } else {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * 删除商品图片
     * @param integer $id
     * @return Response
     */
    public function actionDeleteImage()
    {
        $id = Yii::$app->getRequest()->post('id');
        if ($id = (int) $id) {
            Yii::$app->getDb()->createCommand()->delete('{{%product_image}}', ['id' => $id])->execute();
            $responseBody = [
                'success' => true,
            ];
        } else {
            $responseBody = [
                'success' => false,
                'error' => [
                    'message' => '图片不存在。'
                ]
            ];
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $responseBody,
            ])

        ;
    }

    /**
     * 更新商品图片描述文字
     * 
     * @return Response
     */
    public function actionUpdateImageDescription()
    {
        $request = Yii::$app->getRequest();
        $id = (int) $request->post('id');
        $description = $request->post('description');
        if ($id) {
            Yii::$app->getDb()->createCommand()->update('{{%product_image}}', [ 'description' => $description], [ 'id' => $id])->execute();
            $responseBody = [
                'success' => true,
            ];
        } else {
            $responseBody = [
                'success' => false,
                'error' => [
                    'message' => '图片不存在。'
                ]
            ];
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $responseBody,
        ]);
    }

    /**
     * 获取商品类型关联数据
     * @param integer $typeId
     * @return Response
     */
    public function actionTypeRawData($typeId)
    {
        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => Type::getRawData($typeId),
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
