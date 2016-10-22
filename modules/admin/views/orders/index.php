<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('model', 'Order');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="order-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>   
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'sn',
                'format' => 'raw',
                'value' => function ($model) {
                    return yii\helpers\Html::a($model['sn'], ['view', 'id' => $model['id']]);
                },
                    'contentOptions' => ['class' => 'order-sn'],
                ],
                [
                    'attribute' => 'quantity',
                    'contentOptions' => ['class' => 'number'],
                ],
                [
                    'attribute' => 'total_amount',
                    'contentOptions' => ['class' => 'price'],
                ],
                [
                    'attribute' => 'discount_amount',
                    'contentOptions' => ['class' => 'price'],
                ],
                [
                    'attribute' => 'express_fee',
                    'contentOptions' => ['class' => 'price'],
                ],
                [
                    'attribute' => 'actual_amount',
                    'contentOptions' => ['class' => 'price'],
                ],
                [
                    'attribute' => 'address.linkman',
                    'contentOptions' => ['class' => 'username'],
                ],
                [
                    'attribute' => 'address.tel',
                    'contentOptions' => ['class' => 'tel'],
                ],
                [
                    'attribute' => 'address.address',
                ],
                // 'express_id',
                // 'express_sn',
                // 'status',
                // 'remark',
                // 'tenant_id',
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                    'contentOptions' => ['class' => 'datetime'],
                ],
                [
                    'attribute' => 'creater.username',
                    'headerOptions' => ['class' => 'last'],
                    'contentOptions' => ['class' => 'username'],
                ],
            // 'updated_at',
            // 'updated_by',
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
</div>
