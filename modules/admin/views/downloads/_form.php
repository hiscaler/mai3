<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Download */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="form-outside">
    <div class="download-form form">

        <?php
        $form = ActiveForm::begin();
        ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'path_type')->dropDownList(\app\models\Download::pathTypeOptions()) ?>

        <?= $form->field($model, 'url_path')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'file_path')->fileInput() ?>

        <?= $form->field($model, 'cover_photo_path')->fileInput() ?>

        <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'pay_credits')->textInput() ?>

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),  ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
