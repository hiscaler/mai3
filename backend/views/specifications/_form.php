<?php

use common\models\Option;
use common\models\Specification;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model common\models\Specification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">               

                <?php $form = ActiveForm::begin(); ?>
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-base"> 规格</a></li>
                        <li><a data-toggle="tab" href="#tab-values">规格值</a></li>
                    </ul>

                    <div class="tab-content">

                        <div id="tab-base" class="tab-pane active">
                            <div class="panel-body">
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'type')->radioList(Specification::typeOptions()) ?>

                                <?= $form->field($model, 'ordering')->textInput() ?>

                                <?= $form->field($model, 'status')->checkbox([], null) ?>
                            </div>
                        </div>

                        <div id="tab-values" class="tab-pane">
                            <div class="panel-body">
                                <div class="grid-view">
                                    <table class="table">
                                        <caption>
                                            <a id="btn-dynamic-add-specifications-row" href="javascript:;" class="btn btn-primary btn-xs">添加一行</a>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="width: 80px;">排序</th>
                                                <th>规格值名称</th>
                                                <th style="width: 120px">规格图标</th>
                                                <th>状态</th>
                                                <th class="btns-1 last"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($model->values):
                                                echo Html::hiddenInput('mai3-index-counter', count($model->values), ['id' => 'mai3-index-counter']);
                                                foreach ($model->values as $index => $value):
                                                    ?>
                                                    <tr id="row-<?= $index ?>">
                                                        <td class="ordering">
                                                            <?= $form->field($model, "valuesData[$index][id]", ['template' => '{input}'])->hiddenInput()->label(false) ?>
                                                            <?= $form->field($model, "valuesData[$index][ordering]")->dropDownList(Option::orderingOptions(), ['options' => ['selected' => 'selected']])->label(false) ?>
                                                        </td>
                                                        <td class="text"><?= $form->field($model, "valuesData[$index][text]")->textInput(['maxlength' => 20, 'value' => $value['text']])->label(false) ?></td>
                                                        <td><?= $form->field($model, "valuesData[$index][icon_path]")->fileInput()->label(false) ?></td>
                                                        <td class="boolean">
                                                            <?= $form->field($model, "valuesData[$index][status]")->checkbox([], false)->label(false) ?>

                                                        </td>
                                                        <td>
                                                            <a class="btn-delete-table-row" href="<?= Url::toRoute(['delete-value', 'id' => $value['id']]) ?>" title="删除"><span class="glyphicon glyphicon-trash"></span></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <tr id="row-0">
                                                    <td><?= $form->field($model, "valuesData[0][ordering]")->dropDownList(Option::orderingOptions())->label(false) ?></td>
                                                    <td><?= $form->field($model, "valuesData[0][text]")->textInput(['maxlength' => 20])->label(false) ?></td>
                                                    <td><?= $form->field($model, "valuesData[0][icon_path]")->fileInput()->label(false) ?></td>
                                                    <td class="boolean"><?= $form->field($model, "valuesData[0][status]")->checkbox(['value' => 1], true)->label(false) ?></td>

                                                    <td>Delete</td>
                                                </tr>
                                            <?php endif ?>
                                        </tbody>
                                    </table>
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
</div>
