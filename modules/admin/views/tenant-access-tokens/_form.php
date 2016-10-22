<?php

use app\models\Yad;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TenantAccessToken */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="tenant-access-token-form form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= Html::label(Yii::t('tenant', 'Name')) ?>
            <?= Html::textInput('tenant_name', $model->isNewRecord ? Yad::getTenantName() : $model->tenant->name, ['disabled' => 'disabled', 'class' => 'g-text-medium disabled']) ?>
        </div>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'g-text-medium']) ?>

        <?= $form->field($model, 'access_token')->textInput(['maxlength' => true, 'class' => 'g-text-medium']) ?>

        <?= $form->field($model, 'enabled')->checkbox([], false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
