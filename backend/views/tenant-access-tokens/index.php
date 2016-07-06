<?php

use common\models\Option;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TenantAccessTokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tenant Access Tokens');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="tenant-access-token-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'tenant.name',
                'contentOptions' => ['class' => 'tenant-name'],
            ],
            'title',
            [
                'attribute' => 'access_token',
                'contentOptions' => ['class' => 'access-token'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
            ],
            [
                'attribute' => 'status',
                'format' => 'dataStatus',
                'contentOptions' => ['class' => 'data-status'],
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model) {
                    return $model['creater']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model) {
                    return $model['updater']['nickname'];
                },
                'contentOptions' => ['class' => 'username rb-updated-by']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date rb-updated-at']
            ],
            [
                'attribute' => 'deleted_by',
                'value' => function($model) {
                    return $model['deleter']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'deleted_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {undo}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return $model['status'] != Option::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    },
                    'undo' => function ($url, $model, $key) {
                        return $model['status'] == Option::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-undo"></span>', $url, [
                            'title' => Yii::t('app', 'Undo'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to undo this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    }
                ],
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'buttons-3'],
            ],
        ],
    ]);
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
