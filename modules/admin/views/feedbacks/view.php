<?php

use yii\widgets\DetailView;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model app\models\Feedback */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="feedback-view">

    <?php
    $attributes = [
        [
            'attribute' => 'group_id',
            'value' => Yii::$app->getFormatter()->asGroupName('feedback.group', $model['group_id']),
            'format' => 'raw',
        ],
        'username',
        'tel',
        'email:email',
        'title',
        'message:ntext',
        'ip_address',
        'status:feedbackStatus',
//        [
//            'attribute' => 'created_by',
//            'value' => $model['creater']['nickname']
//        ],
        'created_at:datetime',
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
