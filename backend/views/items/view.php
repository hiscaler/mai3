<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="item-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category_id',
            'brand_id',
            'sn',
            'name',
            'market_price',
            'shop_price',
            'member_price',
            'picture_path',
            'keywords',
            'description:ntext',
            'ordering',
            'clicks_count',
            'sales_count',
            'status',
            'tenant_id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ])
    ?>

</div>
