<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Type */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="item-type-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'ordering',
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
