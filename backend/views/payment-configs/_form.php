<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php $form = ActiveForm::begin();
                echo $form->errorSummary($model);
                ?>

                <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'ordering')->textInput() ?>

                <?= $form->field($model, 'status')->checkbox([], null) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
