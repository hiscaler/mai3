<?php
/* @var $this yii\web\View */
/* @var $model app\models\Ad */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ad',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="ad-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
