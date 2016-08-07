<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => 12]) ?>

        <?php if ($model->isNewRecord): ?>
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 12, 'class' => 'g-text']) ?>

            <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => 12, 'class' => 'g-text']) ?>
        <?php endif; ?>

        <?= $form->field($model, 'nickname')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'status')->dropDownList(User::statusOptions(), ['prompt' => '']) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
