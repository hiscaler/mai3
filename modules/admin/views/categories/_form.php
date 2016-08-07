<?php

use app\models\Category;
use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'type')->dropDownList(Option::categoryTypeOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'parent_id')->dropDownList(Category::getTree('顶级分类')) ?>

        <?= $form->field($model, 'icon_path')->fileInput() ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->checkbox()->label(false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>