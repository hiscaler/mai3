<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="article-view">


    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'alias',
            'title',
            'keywords',
            'description:ntext',
            'content:raw',
            [
                'attribute' => 'picture_path',
                'format' => 'image',
                'value' => $model['picture_path'],
            ],
            'enabled:boolean',
            'created_at:datetime',
            'updated_at:datetime',
            'deleted_at:datetime',
        ],
    ])
    ?>

</div>
