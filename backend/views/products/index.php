<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'common-models-Album'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-album']],
    ['label' => Yii::t('app', '搜索'), 'url' => '#'],
];
?>


<div class="items-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-items-search',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'header' => '#',
                'contentOptions' => ['class' => 'id'],
            ],
            [
                'attribute' => 'ordering',
                'contentOptions' => ['class' => 'ordering'],
            ],
            'category.name',
            'brand.name',
            [
                'attribute' => 'sn',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model['sn'], ['update', 'id' => $model['id']]);
                },
                'contentOptions' => ['class' => 'item-sn'],
            ],
            'name',
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
