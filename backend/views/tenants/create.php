<?php
/* @var $this yii\web\View */
/* @var $model common\models\Tenant */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Tenant'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tenants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="tenant-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
