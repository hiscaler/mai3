<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Feedbacks');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'app-models-Feedback'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-feedback']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="feedback-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-search-feedbacks',
        'linkSelector' => '#grid-view-feedback a',
    ]);
    echo yii\grid\GridView::widget([
        'id' => 'grid-view-feedback',
//        'name' => 'app-models-Feedback',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'group_id',
                'value' => function($model, $key, $index, $grid) {
                    return Yii::$app->getFormatter()->asGroupName('feedback.group', $model['group_id']);
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'group-name'],
            ],
            [
                'attribute' => 'username',
                'contentOptions' => ['class' => 'username'],
            ],
            'tel',
            'email:email',
            'title',
            [
                'attribute' => 'ip_address',
                'contentOptions' => ['class' => 'ip-address'],
            ],
            [
                'attribute' => 'status',
                'format' => 'feedbackStatus',
                'contentOptions' => ['class' => 'data-status'],
            ],
//            [
//                'attribute' => 'created_by',
//                'value' => function($model) {
//                    return $model['creater']['nickname'];
//                },
//                'contentOptions' => ['class' => 'username']
//            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'headerOptions' => array('class' => 'buttons-2 last'),
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>
