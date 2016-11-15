<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="news-view">

    <?php
    $attributes = [
        'ordering',
        [
            'attribute' => 'category_id',
            'value' => $model['category']['name'],
        ],
        'title',
        'short_title',
        'tags',
        'keywords',
        'author',
        'source',
        'description:ntext',
        'newsContent.content:raw',
        'is_picture_news:boolean',
        [
            'attribute' => 'picture_path',
            'format' => 'raw',
            'value' => $model['picture_path'] ? Yii::$app->getFormatter()->asImage($model['picture_path']) . Html::a(Yii::t('app', 'Image Cropper'), ['image-service/crop', 'filename' => Yii::$app->getRequest()->getBaseUrl() . $model['picture_path']], ['class' => 'open-image-cropper-dialog']) : null,
        ],
        'status:dataStatus',
        'enabled:boolean',
        'enabled_comment:boolean',
        'comments_count',
        'clicks_count',
        'up_count',
        'down_count',
        'published_at:datetime',
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
        [
            'attribute' => 'deleted_by',
            'value' => $model['deleter']['nickname']
        ],
        'deleted_at:datetime',
    ];
//    if ($metaItems) {
//        foreach ($metaItems as $attribute => $item) {
//            $attributes[] = [
//                'attribute' => $attribute,
//                'label' => Yii::t('news', Inflector::camel2words(Inflector::id2camel($attribute, '_'))),
//                'value' => $item['value'],
//            ];
//        }
//    }
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]);
    ?>

</div>
