<?php

use common\models\Option;
use common\models\Tenant;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="user-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-user-search',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'username') ?>

            <?php echo $form->field($model, 'status')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
