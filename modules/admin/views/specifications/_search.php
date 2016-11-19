<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SpecificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="specifications-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-specifications-search',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'type')->dropDownList(\app\models\Specification::typeOptions(), ['prompt' => '']) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'enabled')->dropDownList(\app\models\Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
