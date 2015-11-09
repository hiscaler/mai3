<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$baseUrl = Yii::$app->getRequest()->getBaseUrl();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>INSPINIA | Login</title>
        <link href="<?= $baseUrl ?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= $baseUrl ?>/font-awesome.min.css" rel="stylesheet">
        <link href="<?= $baseUrl ?>/css/animate.css" rel="stylesheet">
        <link href="<?= $baseUrl ?>/css/inspinia.css" rel="stylesheet">
    </head>

    <body class="gray-bg">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <div>
                    <h1 class="logo-name">Mai3</h1>
                </div>
                <h3>Welcome to Mai3</h3>
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'm-t']); ?>

                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary block full-width m-b', 'name' => 'login-button']) ?>
                </div>

                <a href="#"><small>忘记密码?</small>

                    <?php ActiveForm::end(); ?>


                    <p class="m-t"> <small><?= Yii::$app->name ?> &copy; <?= date('Y') ?></small> </p>
            </div>
        </div>

        <!-- Mainly scripts -->
        <script src="js/jquery-2.1.1.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>
