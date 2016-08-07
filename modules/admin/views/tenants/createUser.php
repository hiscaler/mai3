<?php
/* @var $this yii\web\View */
/* @var $model app\models\Tenant */

$this->title = $tenant->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tenants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title . ' [ ' . Yii::t('tenant', 'Create Tenant Manage User') . ' ]';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $tenant->id]],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $tenant->id]],
    ['label' => Yii::t('tenant', 'Create Tenant Manage User'), 'url' => ['create-user', 'id' => $tenant->id]],
];
?>


<div class = "user-create">

    <?=
    $this->render('_createTenantUserForm', [
        'model' => $model
    ])
    ?>

</div>