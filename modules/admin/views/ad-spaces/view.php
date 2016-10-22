<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdSpace */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ad Spaces'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="ad-space-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'group_id',
                // 'value' => Yii::$app->getFormatter()->asGroupName('ad.space.group', $model['group_id']),
                'value' => $model['group_id'],
                'format' => 'raw',
            ],
            'alias',
            'name',
            [
                'value' => "{$model['width']}px X {$model['height']}px",
                'label' => 'Size',
            ],
            'description',
            'ads_count',
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
            /*[
                'attribute' => 'deleted_by',
                'value' => $model['deleter']['nickname']
            ],
            'deleted_at:datetime',*/
        ],
    ])
    ?>

</div>
