<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ItemComment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Item Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="item-comment-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'level_text',
            'item.name',
            'username',
            'tel',
            'email:email',
            'message:ntext',
            'return_user_id',
            'return_datetime:datetime',
            'return_message:ntext',
            'enabled:boolean',
            'ip_address:long2ip',
            'created_at:datetime',
            'creater.username',
        ],
    ])
    ?>

</div>
