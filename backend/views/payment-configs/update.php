<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentConfig */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Payment Config',
    ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];

echo $this->render('_form', [
    'model' => $model,
]);

