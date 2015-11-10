<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TenantAccessToken */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tenant Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="tenant-access-token-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tenant.name',
            'title',
            'access_token',
            'enabled:boolean',
            [
                'attribute' => 'created_by',
                'value' => $model['creater']['nickname']
            ],
            'created_at:datetime',
            [
                'attribute' => 'updated_by',
                'value' => $model['updater']['nickname']
            ],
            'updated_at:datetime',
        ],
    ])
    ?>

</div>
