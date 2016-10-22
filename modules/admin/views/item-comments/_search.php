<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemCommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="item-comment-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-search-item-comments',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'level')->dropDownList(\app\models\ItemComment::levelOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'item_name') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'tel') ?>
        </div>

        <?php // echo $form->field($model, 'email') ?>

        <?php // echo $form->field($model, 'return_user_id') ?>

        <?php // echo $form->field($model, 'return_datetime') ?>

        <?php // echo $form->field($model, 'return_message') ?>

        <?php // echo $form->field($model, 'enabled') ?>

        <?php // echo $form->field($model, 'ip_address') ?>

        <?php // echo $form->field($model, 'created_at') ?>

        <?php // echo $form->field($model, 'created_by')  ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
