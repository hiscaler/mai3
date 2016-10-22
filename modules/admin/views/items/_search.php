<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="items-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-search-items',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'id') ?>

            <?= $form->field($model, 'product_id') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'category_id')->dropDownList(app\models\Category::getTree(null, true), ['prompt' => '']) ?>

            <?= $form->field($model, 'brand_id')->dropDownList(app\models\Brand::getList(), ['prompt' => '']) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'sn') ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <?php // echo $form->field($model, 'default') ?>

        <?php // echo $form->field($model, 'enabled') ?>

        <?php // echo $form->field($model, 'status') ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
