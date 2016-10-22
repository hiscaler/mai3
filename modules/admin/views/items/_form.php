<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside">
    <div class="item-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'sn')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'market_price')->textInput() ?>
        
        <?= $form->field($model, 'shop_price')->textInput() ?>

        <?= $form->field($model, 'member_price')->textInput() ?>

        <?= $form->field($model, 'cost_price')->textInput() ?>

        <?= $form->field($model, 'picture_path')->fileInput() ?>

        <?= $form->field($model, 'default')->checkbox([], null) ?>

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>
        
        <?= $form->field($model, 'online')->checkbox([], null) ?>

        <?= $form->field($model, 'view_require_credits')->textInput() ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
