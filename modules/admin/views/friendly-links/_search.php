<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FriendlyLink;
use app\models\Option;

/* @var $this yii\web\View */
/* @var $model app\models\FriendlyLinkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="friendly-link-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-friendly-links-search',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'group_id')->dropDownList(FriendlyLink::groupOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'type')->dropDownList(FriendlyLink::typeOptions(), ['prompt' => '']) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'url') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
