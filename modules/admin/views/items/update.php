<?php
/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => Yii::t('model', 'Item'),
    ]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];
?>
<div class="item-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
