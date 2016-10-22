<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DownloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Downloads');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];

$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/admin';
?>
<div class="download-index">


    <?php Pjax::begin(); ?>  
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            'title',
            [
                'attribute' => 'pay_credits',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'clicks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'downloads_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {download} {delete}',
                'buttons' => [
                    'download' => function ($url, $model, $key) use ($baseUrl) {
                        return Html::a(Html::img($baseUrl . '/images/download.png'), $url, ['data-pjax' => 0]);
                    }
                ],
                'headerOptions' => array('class' => 'buttons-4 last'),
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>

<?php \app\modules\admin\components\JsBlock::begin() ?>
<script type="text/javascript">
    yadjet.actions.toggle("table td.enabled-handler img", "<?= Url::toRoute('toggle') ?>");
</script>
<?php \app\modules\admin\components\JsBlock::end() ?>
