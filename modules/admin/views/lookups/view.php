<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lookup */

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app', 'Lookups'), 'url' => ['index']],
    $model->label . (!empty($model->description) ? '「' . $model->description . '」' : ''),
];

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="lookup-view">
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'label',
                'contentOptions' => ['class' => 'lookup-label'],
            ],
            'description',
            'value:ntext',
            'return_type_text',
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
//            [
//                'attribute' => 'deleted_by',
//                'value' => $model['deleter']['nickname']
//            ],
        ],
    ])
    ?>

</div>
