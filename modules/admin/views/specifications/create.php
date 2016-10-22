<?php

/* @var $this yii\web\View */
/* @var $model app\models\Specification */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Specifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];

echo $this->render('_form', [
    'model' => $model,
]);
