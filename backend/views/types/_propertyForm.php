<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TypeProperty */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-property-form form-outside">
    <div class="form">
        <?php $form = ActiveForm::begin(); ?>
        
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'return_type')->dropDownList(\common\models\TypeProperty::returnTypeOptions()) ?>

        <?= $form->field($model, 'input_method')->dropDownList(\common\models\TypeProperty::inputMethodOptions()) ?>

        <?= $form->field($model, 'input_values')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'input_default_value')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ordering')->dropDownList(common\models\Option::orderingOptions()) ?>

        <?= $form->field($model, 'status')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
