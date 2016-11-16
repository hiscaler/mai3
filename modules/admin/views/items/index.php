<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Items');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', '搜索'), 'url' => '#'],
];
?>
<div class="item-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-search-items',
        'timeout' => 6000,
    ]);
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sn',
                'contentOptions' => ['class' => 'item-sn'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class=\"pk\">[ {$model['id']} ]</span>" . yii\helpers\Html::a($model['name'], ['view', 'id' => $model['id']]);
                },
            ],
            [
                'attribute' => 'market_price',
                'contentOptions' => ['class' => 'price'],
            ],
            [
                'attribute' => 'member_price',
                'contentOptions' => ['class' => 'price'],
            ],
            // 'cost_price',
            // 'picture_path',
            [
                'attribute' => 'clicks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'favorites_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'sales_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'stocks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'default',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer default-handler'],
            ],
            [
                'attribute' => 'online',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer online-handler'],
            ],
            [
                'attribute' => 'on_off_datetime',
                'format' => 'datetime',
                'contentOptions' => ['class' => 'rb-on-off-datetime datetime'],
            ],
            [
                'attribute' => 'view_require_credits',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => array('class' => 'buttons-3 last'),
            ],
        ],
    ]);
    ?>
    <?php \app\modules\admin\components\JsBlock::begin() ?>
        <script type="text/javascript">
            yadjet.actions.toggle("table td.online-handler img", "<?= yii\helpers\Url::toRoute('toggle') ?>");
            yadjet.actions.toggle("table td.default-handler img", "<?= yii\helpers\Url::toRoute('set-default') ?>");
        </script>
    <?php \app\modules\admin\components\JsBlock::end() ?>
    <?php Pjax::end(); ?>
</div>
