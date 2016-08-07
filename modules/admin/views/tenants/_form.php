<?php

use app\models\Yad;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tenant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="tenant-form form">

        <?php
        $form = ActiveForm::begin();
        $keyOptions = [
            'maxlength' => 255
        ];
        if (!$model->isNewRecord) {
            $keyOptions['disabled'] = 'disabled';
            $keyOptions['class'] = 'disabled';
        }
        ?>

        <?php
        if (!$model->isNewRecord) {
            echo $form->field($model, 'key')->textInput($keyOptions);
        }
        ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'language')->dropDownList(Yad::getLanguages(), ['prompt' => '']) ?>

        <?= $form->field($model, 'timezone')->dropDownList(Yad::getTimezones(), ['prompt' => '']) ?>

        <?= $form->field($model, 'date_format')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'time_format')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'datetime_format')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'domain_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'class' => 'form-control g-text-large']) ?>

        <?= $form->field($model, 'status')->checkbox([], false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
