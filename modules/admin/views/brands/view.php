<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Brand */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="brand-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'alias',
            'name',
            'icon_path',
            'description:ntext',
            'ordering',
            'tenant_id',
            'status',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ])
    ?>

</div>
