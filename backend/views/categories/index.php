<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', '列表'), 'url' => ['index']],
    ['label' => Yii::t('app', '添加'), 'url' => ['create']],
    ['label' => Yii::t('app', '搜索'), 'url' => '#'],
];
?>
<div class="ibox float-e-margins">

    <div class="ibox-content">


        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'contentOptions' => ['class' => 'id'],
                ],
                'type',
                'alias',
                'name',
                'parent_id',
                // 'level',
                // 'parent_ids',
                // 'parent_names',
                'description:ntext',
                [
                    'attribute' => 'status',
                    'format' => 'boolean',
                    'contentOptions' => ['class' => 'boolean pointer'],
                ],
                [
                    'attribute' => 'created_by',
                    'value' => function($model) {
//                                return $model['creater']['nickname'];
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
//                                return $model['updater']['nickname'];
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
                    'headerOptions' => array('class' => 'btns-3 last'),
                ],
            ],
        ]);
        ?>

    </div>
</div>