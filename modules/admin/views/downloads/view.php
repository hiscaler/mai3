<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Download */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Downloads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="download-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'file_path',
                'format' => 'raw',
                'value' => Html::a(Yii::t('download', 'Download'), ['download', 'id' => $model['id']]),
            ],
            'cover_photo_path:image',
            'keywords',
            'description:ntext',
            'pay_credits',
            'clicks_count',
            'downloads_count',
            'enabled:boolean',
            'created_at:datetime',
            'created_by',
            'updated_at:datetime',
            'updated_by',
            'deleted_at:datetime',
            'deleted_by',
        ],
    ])
    ?>

</div>
