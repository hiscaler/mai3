<?php
$this->title = Yii::t('app', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-signup">
    <div class="title">会员登陆</div>
    <?php $form = \yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>



    <div class="form-group">
        <?= \yii\helpers\Html::submitButton(Yii::t('app', '立即登录'), ['class' => 'btn-signup']) ?>
    </div>

    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
