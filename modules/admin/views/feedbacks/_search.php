<?php

use app\models\Feedback;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="feedback-search form">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'form-search-feedbacks',
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'group_id')->dropDownList(Feedback::groupOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'username') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'tel') ?>

            <?= $form->field($model, 'email') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'status')->dropDownList(Feedback::statusOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
