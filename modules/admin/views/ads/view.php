<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ad */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="ad-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'space_id',
                'value' => $model['space']['name']
            ],
            'name',
            'url',
            'type_text',
//            'file_path:AdFile',
            'file_path',
            'text:raw',
            'begin_datetime:datetime',
            'end_datetime:datetime',
            'message',
            'views_count',
            'clicks_count',
            'enabled:boolean',
            [
                'attribute' => 'created_by',
                'value' => $model['creater']['nickname']
            ],
            'created_at:datetime',
            [
                'attribute' => 'updated_by',
                'value' => $model['updater']['nickname']
            ],
            'updated_at:datetime',
        ],
    ])
    ?>

</div>
