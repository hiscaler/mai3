<?php

/* @var $this yii\web\View */
/* @var $model common\models\Type */

$this->title = Yii::t('app', 'Create Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];

echo $this->render('_form', [
    'model' => $model,
    'specifications' => $specifications,
]);

