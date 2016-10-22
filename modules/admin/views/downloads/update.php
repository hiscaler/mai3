<?php
/* @var $this yii\web\View */
/* @var $model app\models\Download */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Download',
    ]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Downloads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');


$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];
?>
<div class="download-update">

<?=
$this->render('_form', [
    'model' => $model,
])
?>

</div>
