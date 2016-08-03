<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="payment-config-form form">

        <?php
        $form = ActiveForm::begin();
        echo $form->errorSummary($model);
        ?>

        <?= $form->field($model, 'key')->dropDownList(\common\models\PaymentConfig::keyOptions(), ['maxlength' => true, 'disabled' => !$model->isNewRecord ? 'disabled' : null, 'prompt' => '']) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?php
        $configs = \yii\helpers\ArrayHelper::map($model->configs, 'name', 'value');
        $pay = [];
        $payDataFormat = isset(Yii::$app->params['pay'][$model->key]) ? Yii::$app->params['pay'][$model->key] : [];
        foreach ($payDataFormat as $key => $value) {
            $pay[$key]['description'] = isset($value['description']) ? $value['description'] : $value['name'];
            $pay[$key]['label'] = isset($value['label']) ? $value['label'] : $value['name'];
            $pay[$key]['name'] = $value['name'];
            $pay[$key]['type'] = $value['type'];
            $pay[$key]['items'] = isset($value['items']) ? $value['items'] : [];
            if (isset($configs[$value['name']])) {
                $pay[$key]['value'] = $configs[$value['name']];
            } else {
                $pay[$key]['value'] = $value['value'];
            }
            if ($pay[$key]['type'] == 'select' || $pay[$key]['type'] == 'radiobox') {
                $pay[$key]['range'] = $pay[$key]['name'];
            }
        }
        foreach ($pay as $p) {
            if ($p['type'] == 'select' && isset($p['items']) && $p['items']) {
                echo $form->field($model, 'configs[]')->dropDownList($p['items'])->label($p['label'])->hint($p['description']);
            } elseif ($p['type'] == 'radio' && isset($p['items']) && $p['items']) {
                echo $form->field($model, 'configs[]')->radioList($p['items'], ['unselect' => 0])->label($p['label'])->hint($p['description']);
            } else {
                echo $form->field($model, 'configs[]')->textInput(['value' => $p['value']])->label($p['label'])->hint($p['description']);
            }
        }
        ?>

        <?= $form->field($model, 'ordering')->dropDownList(\common\models\Option::orderingOptions()) ?>

        <?= $form->field($model, 'status')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
