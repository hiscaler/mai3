<?php
/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => Yii::t('model', 'Order'),
    ]) . $model->sn;
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Order'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
