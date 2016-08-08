<?php
$this->title = Yii::t('app', 'Signin');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-signup">
    <div class="title">注册新会员</div>
    <?php $form = \yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->hint('6-12位数字或字母a-z组合') ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint('6-12位数字或字母a-z组合') ?>

    <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= \yii\helpers\Html::submitButton(Yii::t('app', '立即提交注册'), ['class' => 'btn-signup']) ?>
    </div>

    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
