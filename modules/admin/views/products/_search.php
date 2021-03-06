<?php

use app\models\Brand;
use app\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="products-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-search-products',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'category_id')->dropDownList(Category::getTree(\app\models\Lookup::getValue('system.models.category.type.product', 0), null, true), ['prompt' => '']) ?>

            <?= $form->field($model, 'brand_id')->dropDownList(Brand::getList(), ['prompt' => '']) ?>
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
