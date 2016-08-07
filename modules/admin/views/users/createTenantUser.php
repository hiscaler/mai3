<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */
$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Tenant User'),
        ]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('tenant', 'Create Tenant Manage User');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>

<div class="user-create">

    <?=
    $this->render('_createTenantUserForm', [
        'model' => $model
    ])
    ?>

</div>
