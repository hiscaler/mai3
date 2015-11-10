<?php
/* @var $this yii\web\View */
/* @var $model common\models\TenantAccessToken */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Tenant Access Token'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tenant Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="tenant-access-token-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
