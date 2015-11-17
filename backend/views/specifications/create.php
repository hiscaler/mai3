<?php

/* @var $this yii\web\View */
/* @var $model common\models\Specification */

$this->title = Yii::t('app', 'Create Specification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Specifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];

echo $this->render('_form', [
    'model' => $model,
    'value' => $value,
]);
