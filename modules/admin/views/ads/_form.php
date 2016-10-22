<?php

use app\models\Ad;
use app\models\AdSpace;
use yadjet\datePicker\my97\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="ad-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
        ]);
        ?>

        <?= $form->field($model, 'space_id')->dropDownList(AdSpace::spaceOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList(Ad::typeOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'file_path')->fileInput() ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

        <?=
        DatePicker::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'begin_datetime',
            'pickerType' => 'datetime',
        ]);
        ?>

        <?=
        DatePicker::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'end_datetime',
            'pickerType' => 'datetime',
        ]);
        ?>

        <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
