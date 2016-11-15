<?php
/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'News'),
        ]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="news-create">

    <?=
    $this->render('_form', [
        'model' => $model,
        'newsContent' => $newsContent,
    ])
    ?>

</div>
