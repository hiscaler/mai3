<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Meta */

$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meta'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="meta-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'object_name',
            'key',
            'label',
            'description',
            'input_type_text',
            'return_value_type_text',
            'default_value',
            'enabled:boolean',
//            'created_by',
            'created_at:datetime',
//            'updated_by',
            'updated_at:datetime',
//            'deleted_by',
            'deleted_at:datetime',
        ],
    ])
    ?>

</div>
