<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentConfig */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="payment-config-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            'name',
            'config:ntext',
            'description:ntext',
            'ordering',
            'enabled',
            'created_at:datetime',
            'created_by',
            'updated_at:datetime',
            'updated_by',
        ],
    ])
    ?>

</div>
