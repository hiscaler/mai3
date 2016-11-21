<?php

use app\modules\admin\components\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'app-models-Product'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-products']],
    ['label' => Yii::t('app', '搜索'), 'url' => '#'],
];
?>


<div class="products-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-search-products',
        'timeout' => 6000,
        'linkSelector' => '#grid-view-products a',
    ]);
    echo GridView::widget([
        'id' => 'grid-view-products',
        'name' => 'app-models-Product',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'header' => '#',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'ordering',
                'contentOptions' => ['class' => 'ordering'],
            ],
            'type.name',
            'category.name',
            'brand.name',
            [
                'attribute' => 'sn',
                'contentOptions' => ['class' => 'item-sn'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class=\"pk\">[ {$model['id']} ]</span>" . Html::a($model['name'], ['update', 'id' => $model['id']]);
                }
            ],
            [
                'attribute' => 'market_price',
                'contentOptions' => ['class' => 'price'],
            ],
            [
                'attribute' => 'shop_price',
                'contentOptions' => ['class' => 'price'],
            ],
            [
                'attribute' => 'member_price',
                'contentOptions' => ['class' => 'price'],
            ],
            [
                'attribute' => 'clicks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'sales_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'status',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer'],
            ],
            [
                'attribute' => 'online',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer'],
            ],
            [
                'attribute' => 'on_off_datetime',
                'format' => 'datetime',
                'contentOptions' => ['class' => 'datetime'],
            ],
//                [
//                    'attribute' => 'view_require_credits',
//                    'contentOptions' => ['class' => 'nubmer'],
//                ],
            [
                'attribute' => 'created_by',
                'value' => function($model) {
//                                return $model['creater']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model) {
//                                return $model['updater']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => array('class' => 'buttons-3 last'),
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>
