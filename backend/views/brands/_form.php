<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?php
                $form = ActiveForm::begin([
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                            'template' => "{label}\n<div class=\"col-lg-10\">{input}{hint}{error}</div>"
                        ]
                ]);
                ?>

                <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
                
                <div class="hr-line-dashed"></div>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                
                <div class="hr-line-dashed"></div>

                <?= $form->field($model, 'icon_path')->fileInput() ?>
                
                <div class="hr-line-dashed"></div>

                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                
                <div class="hr-line-dashed"></div>

                <?= $form->field($model, 'ordering')->textInput() ?>   
                
                <div class="hr-line-dashed"></div>

                <?= $form->field($model, 'status')->checkbox([], null) ?>
                
                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
