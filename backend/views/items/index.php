<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Items');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', '搜索'), 'url' => '#'],
];
?>
<div class="item-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?> 
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'sn',
                'contentOptions' => ['class' => 'sn'],
            ],
            'name',
            [
                'attribute' => 'market_price',
                'contentOptions' => ['class' => 'price'],
            ],
            // 'member_price',
            // 'cost_price',
            // 'picture_path',
            [
                'attribute' => 'clicks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'favorites_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'sales_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'stocks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'default',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean'],
            ],
            [
                'attribute' => 'status',
                'contentOptions' => ['class' => 'data-status'],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
