<?php

use common\models\Tenant;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form user-form">

        <?php $form = ActiveForm::begin(); ?>       

        <?= $form->field($model, 'username')->textInput(['maxlength' => 12]) ?>

        <?= $form->field($model, 'user_group_id')->dropDownList(Tenant::userGroups(), ['prompt' => '']) ?>

        <?= $form->field($model, 'role')->dropDownList(User::roleOptions(), ['prompt' => '']) ?>
        
        <?= $form->field($model, 'rule_id')->dropDownList(Tenant::workflowRules(), ['prompt' => '']) ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'OK'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
