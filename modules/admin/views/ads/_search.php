<?php

use app\models\Ad;
use app\models\AdSpace;
use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="ad-search form">

        <?php
        $form = ActiveForm::begin([
            'id' => 'form-search-ads',
            'action' => ['index'],
            'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'space_id')->dropDownList(AdSpace::spaceOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'type')->dropDownList(Ad::typeOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <?php // echo $form->field($model, 'begin_datetime') ?>

        <?php // echo $form->field($model, 'end_datetime') ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
