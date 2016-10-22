<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DownloadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="download-search">

    <?php
    $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
    ]);
    ?>

    <div class="entry">
        <div class="column">
            <?= $form->field($model, 'path_type')->dropDownList(\app\models\Download::pathTypeOptions(), ['prompt' => '']) ?>
        </div>

        <div class="column">
            <?= $form->field($model, 'title') ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'pay_credits') ?>

    <?php // echo $form->field($model, 'clicks_count') ?>

    <?php // echo $form->field($model, 'downloads_count') ?>

    <?php // echo $form->field($model, 'enabled') ?>

    <?php // echo $form->field($model, 'tenant_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by')  ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
