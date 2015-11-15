<?php

/* @var $this yii\web\View */
/* @var $model common\models\PaymentConfig */

$this->title = Yii::t('app', 'Create Payment Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];

echo $this->render('_form', [
    'model' => $model,
]);
