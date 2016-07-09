<?php

use common\models\Brand;
use common\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="items-search form">

        <?php
        $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'id' => 'form-items-search',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'category_id')->dropDownList(Category::getMap(null, true), ['prompt' => '']) ?>

            <?= $form->field($model, 'brand_id')->dropDownList(Brand::getMap(), ['prompt' => '']) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'sn') ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <?php // echo $form->field($model, 'status') ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
