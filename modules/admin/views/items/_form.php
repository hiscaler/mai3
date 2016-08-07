<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'market_price')->textInput() ?>

    <?= $form->field($model, 'member_price')->textInput() ?>

    <?= $form->field($model, 'cost_price')->textInput() ?>

    <?= $form->field($model, 'picture_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clicks_count')->textInput() ?>

    <?= $form->field($model, 'favorites_count')->textInput() ?>

    <?= $form->field($model, 'sales_count')->textInput() ?>

    <?= $form->field($model, 'stocks_count')->textInput() ?>

    <?= $form->field($model, 'default')->textInput() ?>

    <?= $form->field($model, 'enabled')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
