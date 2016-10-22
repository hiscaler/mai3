<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'icon_path')->fileInput() ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'ordering')->dropDownList(\app\models\Option::orderingOptions()) ?>   

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
