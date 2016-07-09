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

<div class="form-outside">
    <div class="specifications-form form">            

        <?php $form = ActiveForm::begin(); ?>
        <div class="tabs-container">
            <ul class="tabs-common">
                <li class="active"><a data-toggle="tab-base" href="#tab-base"> 规格</a></li>
                <li><a data-toggle="tab-values" href="#tab-values">规格值</a></li>
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

                <div id="tab-values" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <div class="grid-view">
                            <table class="table">
                                <caption>
                                    <a id="btn-dynamic-add-specifications-row" href="javascript:;" class="btn">添加一行</a>
                                </caption>
                                <thead>
                                    <tr>
                                        <th>排序</th>
                                        <th>规格值名称</th>
                                        <th style="width: 120px">规格图标</th>
                                        <th>状态</th>
                                        <th class="button-1 last"></th>
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
                                                    <?= $form->field($model, "valuesData[$index][ordering]", ['template' => '{input}'])->dropDownList(Option::orderingOptions(), ['options' => ['selected' => 'selected']])->label(false) ?>
                                                </td>
                                                <td><?= $form->field($model, "valuesData[$index][text]", ['template' => '{input}'])->textInput(['maxlength' => 20, 'value' => $value['text']])->label(false) ?></td>
                                                <td><?= $form->field($model, "valuesData[$index][icon_path]", ['template' => '{input}'])->fileInput()->label(false) ?></td>
                                                <td class="boolean">
                                                    <?= $form->field($model, "valuesData[$index][status]", ['template' => '{input}'])->checkbox([], false)->label(false) ?>

                                                </td>
                                                <td class="btn-render">
                                                    <a class="btn-delete-table-row" href="<?= Url::toRoute(['delete-value', 'id' => $value['id']]) ?>" title="删除"><span class="glyphicon glyphicon-trash"></span></a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    else:
                                        echo Html::hiddenInput('mai3-index-counter', 1, ['id' => 'mai3-index-counter']);
                                        ?>
                                        <tr id="row-0">
                                            <td class="ordering"><?= $form->field($model, "valuesData[0][ordering]", ['template' => '{input}'])->dropDownList(Option::orderingOptions())->label(false) ?></td>
                                            <td><?= $form->field($model, "valuesData[0][text]", ['template' => '{input}'])->textInput(['maxlength' => 20])->label(false) ?></td>
                                            <td><?= $form->field($model, "valuesData[0][icon_path]", ['template' => '{input}'])->fileInput()->label(false) ?></td>
                                            <td class="boolean"><?= $form->field($model, "valuesData[0][status]", ['template' => '{input}'])->checkbox([], false)->label(false) ?></td>

                                            <td class="btn-render"></td>
                                        </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group buttons">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
