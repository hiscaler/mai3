<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TenantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tenants');
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/admin';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="tenant-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-search-tenants',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'key',
                'format' => 'raw',
                'value' => function ($model) {
                     return Html::a($model['key'], ['update', 'id' => $model['id']]);
                },
                'contentOptions' => ['class' => 'tenant-key'],
            ],
            [
                'attribute' => 'name',
                'contentOptions' => ['class' => 'tenant-name'],
            ],
            [
                'attribute' => 'language',
                'value' => function ($model) {
                    return Yii::t('language', $model['language']);
                },
                'contentOptions' => ['class' => 'tenant-language'],
            ],
            [
                'attribute' => 'timezone',
                'contentOptions' => ['class' => 'timezone center'],
            ],
            [
                'attribute' => 'domain_name',
                'contentOptions' => ['class' => 'domain-name'],
            ],
            'description',
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
                'contentOptions' => ['class' => 'username rb-updated-by']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date rb-updated-at']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {users} {access-tokens} {update}',
                'buttons' => [
                    'users' => function ($url, $model, $key) use ($baseUrl) {
                        return Html::a(Html::img($baseUrl . '/images/users.png'), ['view', 'id' => $model['id'], 'tab' => 'users'], ['data-pjax' => 0, 'class' => 'user-auth']);
                    },
                    'access-tokens' => function ($url, $model, $key) use ($baseUrl) {
                        return Html::a(Html::img($baseUrl . '/images/access-token.png'), ['view', 'id' => $model['id'], 'tab' => 'access-tokens'], ['data-pjax' => 0, 'title' => Yii::t('app', 'Tenant Access Tokens')]);
                    },
                ],
                'headerOptions' => ['class' => 'buttons-4 last'],
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
