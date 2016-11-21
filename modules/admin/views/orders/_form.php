<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="form-outside">
    <div class="form order-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'sn')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

        <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

        <?= $form->field($model, 'discount_amount')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'express_fee')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'actual_amount')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address_id')->textInput() ?>

        <?= $form->field($model, 'express_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'express_sn')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList(\app\models\Order::statusOptions()) ?>

        <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
