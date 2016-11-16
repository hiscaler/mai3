<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SpecificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Specifications');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="specifications-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class=\"pk\">[ {$model['id']} ]</span>" . yii\helpers\Html::a($model['name'], ['update', 'id' => $model['id']]);
                }
            ],
            [
                'attribute' => 'ordering',
                'contentOptions' => ['class' => 'ordering'],
            ],
            [
                'attribute' => 'type_text',
                'contentOptions' => ['class' => 'specification-type center'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
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
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => array('class' => 'buttons-3 last'),
            ],
        ],
    ]);
    ?>

</div>

<?php \app\modules\admin\components\JsBlock::begin() ?>
    <script type="text/javascript">
        yadjet.actions.toggle("table td.enabled-handler img", "<?= yii\helpers\Url::toRoute('toggle') ?>");
    </script>
<?php \app\modules\admin\components\JsBlock::end() ?>