<?php

/* @var $this yii\web\View */
/* @var $model common\models\Type */

$this->title = Yii::t('app', 'Create Type Property');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_propertyForm', [
    'model' => $model,
]);

