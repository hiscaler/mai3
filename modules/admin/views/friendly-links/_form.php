<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FriendlyLink;

/* @var $this yii\web\View */
/* @var $model app\models\FriendlyLink */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside form-layout-column">
    <div class="friendly-link-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
        ]);
        ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'group_id')->dropDownList(FriendlyLink::groupOptions(), ['prompt' => '']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'type')->dropDownList(FriendlyLink::typeOptions()) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <div class="row">

            <div class="col-md-3">
                <?= $form->field($model, 'url_open_target')->dropDownList(FriendlyLink::urlOpenTargetOptions()) ?>
            </div>

            <div class="col-md-3">
                <?php
                $imagePreview = null;
                $imagePath = $model->logo_path;
                if (!$model->isNewRecord && !empty($imagePath)) {
                    if ($model->_fileUploadConfig['thumb']['generate']) {
                        $linkOptions = [
                            'width' => $model->_fileUploadConfig['thumb']['width'],
                            'height' => $model->_fileUploadConfig['thumb']['height']
                        ];
                    } else {
                        $linkOptions = [];
                    }
                    $imagePath = Yii::$app->getRequest()->getBaseUrl() . $imagePath;
                    $imagePreview = Html::a(Html::img($imagePath, $linkOptions), $imagePath, ['target' => '_blank']);
                    $imagePreview = Html::tag('div', $imagePreview, ['class' => 'image-preview']);
                }
                echo $form->field($model, 'logo_path', [
                    'template' => "{label}\n{input}{$imagePreview}\n{hint}\n{error}"
                ])->fileInput()
                ?>
            </div>
            
            <div class="col-md-3">
                <?= $form->field($model, 'ordering')->textInput() ?>
            </div>
            
            <div class="col-md-3">
                <?= $form->field($model, 'enabled')->checkbox([], null) ?>
            </div>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
