<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdSpace */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="ad-space-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'group_id')->dropDownList(\app\models\AdSpace::groupOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'width')->textInput() ?>

        <?= $form->field($model, 'height')->textInput() ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'enabled')->checkBox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
