<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TenantAccessTokenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="tenant-access-token-search form">

        <?php
        $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>

        <?= $form->field($model, 'title') ?>

        <?= $form->field($model, 'access_token') ?>

        <?= $form->field($model, 'enabled') ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
