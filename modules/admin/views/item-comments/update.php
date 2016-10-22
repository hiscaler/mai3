<?php
/* @var $this yii\web\View */
/* @var $model app\models\ItemComment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => Yii::t('model', 'Item Comment'),
    ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];
?>
<div class="item-comment-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
