<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Items');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'common-models-Album'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-album']],
    ['label' => Yii::t('app', '搜索'), 'url' => '#'],
];
?>


<div class="ibox float-e-margins">

    <div class="ibox-title form-search" style="display: none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <div class="ibox-content">

        <?=
        GridView::widget([
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
                        'headerOptions' => array('class' => 'btns-3 last'),
                    ],
                ],
            ]);
            ?>

    </div>
</div>
