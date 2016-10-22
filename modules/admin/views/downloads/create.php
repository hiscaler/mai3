<?php
/* @var $this yii\web\View */
/* @var $model app\models\Download */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Downloads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="download-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
