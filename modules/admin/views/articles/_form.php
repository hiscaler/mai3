<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form">
        <div class="article-form">

            <?php
            $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
            ]);
            ?>

            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?=
            \yadjet\editor\UEditor::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'content',
            ])
            ?>

            <?php
            $template = '{label}{input}';
            if (!$model->isNewRecord && !empty($model->picture_path)) {
                $template .= Html::a(Yii::t('app', 'Delete'), ['remove-picture', 'id' => $model->id], ['class' => 'ajax']);
            }
            $template .= '{error}{hint}';
            echo $form->field($model, 'picture_path', [
                'template' => $template
            ])->fileInput()
            ?>

            <?= $form->field($model, 'enabled')->checkbox([], null) ?>

            <div class="form-group buttons">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
