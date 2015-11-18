<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ItemType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php $form = ActiveForm::begin(); ?>

                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-base">商品类型</a></li>
                        <li><a data-toggle="tab" href="#tab-brands">关联品牌</a></li>
                        <li><a data-toggle="tab" href="#tab-specifications">关联规格</a></li>
                    </ul>

                    <div class="tab-content">

                        <div id="tab-base" class="tab-pane active">
                            <div class="panel-body">
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'ordering')->dropDownList(common\models\Option::orderingOptions()) ?>

                                <?= $form->field($model, 'status')->checkbox([], false) ?>
                            </div>
                        </div>

                        <div id="tab-brands" class="tab-pane">
                            <div class="panel-body">
                                <?= $form->field($model, 'brandList[]')->checkboxList(\common\models\Brand::getMap(false)) ?>
                            </div>
                        </div>

                        <div id="tab-specifications" class="tab-pane">
                            <div class="panel-body">
                                specifications
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
