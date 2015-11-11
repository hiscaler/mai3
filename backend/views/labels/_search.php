<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\LabelSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="attribute-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-attribute-search',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'alias') ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'status')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
