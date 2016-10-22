<?php

use app\modules\admin\widgets\TenantTabs;

/* @var $this yii\web\View */
/* @var $model app\models\Tenant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tenants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
    ['label' => Yii::t('tenant', 'Create Tenant Manage User'), 'url' => ['create-user', 'id' => $model->id]],
    ['label' => Yii::t('tenant', 'Create Tenant Access Token'), 'url' => ['tenant-access-tokens/create']],
];

echo TenantTabs::widget([
    'model' => $model
]);
