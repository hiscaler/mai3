<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ads');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'common-models-Ad'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-ads']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
    <div class="ad-index">

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?php
        Pjax::begin([
            'formSelector' => '#form-search-ads',
            'linkSelector' => '#grid-view-ads a',
        ]);
        echo yii\grid\GridView::widget([
            'id' => 'grid-view-ads',
            'tableOptions' => [
                'class' => 'table table-striped'
            ],
//            'name' => 'common-models-Ad',
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['class' => 'serial-number']
                ],
                [
                    'attribute' => 'space_id',
                    'value' => function ($model) {
                        return '[ ' . $model['space']['alias'] . ' ] ' . $model['space']['name'];
                    },
                    'contentOptions' => ['class' => 'ad-space-name'],
                ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model['name'], ['update', 'id' => $model['id']]);
                    }
                ],
                [
                    'attribute' => 'ad_type',
                    'contentOptions' => ['class' => 'ad-type'],
                ],
                [
                    'attribute' => 'begin_datetime',
                    'format' => 'date',
                    'contentOptions' => ['class' => 'date'],
                ],
                [
                    'attribute' => 'end_datetime',
                    'format' => 'date',
                    'contentOptions' => ['class' => 'date'],
                ],
                'message',
                [
                    'attribute' => 'views_count',
                    'contentOptions' => ['class' => 'number'],
                ],
                [
                    'attribute' => 'hits_count',
                    'contentOptions' => ['class' => 'number'],
                ],
                [
                    'attribute' => 'status',
//                    'format' => 'dataStatus',
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
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update}',
                    'headerOptions' => ['class' => 'last'],
                    'contentOptions' => ['class' => 'buttons-3'],
                ],
            ],
        ]);
        Pjax::end();
        ?>

    </div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
