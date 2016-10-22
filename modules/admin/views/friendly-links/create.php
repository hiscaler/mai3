<?php
/* @var $this yii\web\View */
/* @var $model app\models\FriendlyLink */

$this->title = Yii::t('app', 'Create {modelClass}', [
        'modelClass' => Yii::t('model', 'Friendly Link'),
    ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Friendly Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']]
];
?>
<div class="friendly-link-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
