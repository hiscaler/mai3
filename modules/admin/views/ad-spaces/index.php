<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdSpaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$formatter = Yii::$app->getFormatter();
$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/admin';

$this->title = Yii::t('app', 'Ad Spaces');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'common-models-AdSpace'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-ad-spaces']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="ad-space-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-search-ad-spaces',
        'linkSelector' => '#grid-view-ad-spaces a',
    ]);
    echo yii\grid\GridView::widget([
        'id' => 'grid-view-ad-spaces',
        'tableOptions' => [
            'class' => 'table table-striped'
        ],
//        'name' => 'common-models-AdSpace',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'group_id',
                'value' => function ($model, $key, $index, $grid) use ($formatter) {
                    //return $formatter->asGroupName('ad.space.group', $model['group_id']);
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'group-name'],
            ],
            [
                'attribute' => 'alias',
                'contentOptions' => ['class' => 'ad-space-alias'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['name'], ['update', 'id' => $model['id']]);
                },
                    'contentOptions' => ['class' => 'ad-space-name']
                ],
                [
                    'label' => Yii::t('adSpace', 'Size'),
                    'value' => function ($model) {
                        return "{$model['width']}px X {$model['height']}px";
                    },
                    'contentOptions' => ['class' => 'ad-space-size center'],
                ],
                'description',
                [
                    'attribute' => 'ads_count',
                    'contentOptions' => ['class' => 'number'],
                ],
                [
                    'attribute' => 'status',
                    'contentOptions' => ['class' => 'data-status'],
                ],
                [
                    'attribute' => 'enabled',
                    'format' => 'boolean',
                    'contentOptions' => ['class' => 'boolean enabled-handler pointer']
                ],
                [
                    'attribute' => 'created_by',
                    'value' => function ($model) {
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
                    'value' => function ($model) {
                        return $model['updater']['nickname'];
                    },
                    'contentOptions' => ['class' => 'username']
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'date',
                    'contentOptions' => ['class' => 'date']
                ],
                /* [
                  'attribute' => 'deleted_by',
                  'value' => function ($model) {
                  return $model['deleter']['nickname'];
                  },
                  'contentOptions' => ['class' => 'username']
                  ],
                  [
                  'attribute' => 'deleted_at',
                  'format' => 'date',
                  'contentOptions' => ['class' => 'date']
                  ], */
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {photos} {update} {delete} {undo}',
                    'buttons' => [
                        'photos' => function ($url, $model, $key) use ($baseUrl) {
                            return Html::a(Html::img($baseUrl . '/images/ads.png'), ['ads/index', 'AdSearch[spaceId]' => $model['id']], ['title' => Yii::t('app', 'Ads')]);
                        },
//                        'delete' => function ($url, $model, $key) {
//                            return $model['status'] != Option::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
//                                'title' => Yii::t('yii', 'Delete'),
//                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
//                                'data-method' => 'post',
//                                'data-pjax' => '0',
//                            ]) : '';
//                        },
//                        'undo' => function ($url, $model, $key) {
//                            return $model['status'] == Option::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-undo"></span>', $url, [
//                                'title' => Yii::t('app', 'Undo'),
//                                'data-confirm' => Yii::t('app', 'Are you sure you want to undo this item?'),
//                                'data-method' => 'post',
//                                'data-pjax' => '0',
//                            ]) : '';
//                        }
                        ],
                        'headerOptions' => ['class' => 'last'],
                        'contentOptions' => ['class' => 'buttons-4'],
                    ],
                ],
            ]);
            Pjax::end();
            ?>

        </div>

        <?php
        $this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
        