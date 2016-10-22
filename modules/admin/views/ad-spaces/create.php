<?php
/* @var $this yii\web\View */
/* @var $model app\models\AdSpace */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ad Space',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ad Spaces'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="ad-space-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
