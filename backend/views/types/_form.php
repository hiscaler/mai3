<?php

use common\models\Brand;
use common\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Type */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="tenant-form form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="tabs-container">
            <ul class="tabs-common">
                <li class="active"><a data-toggle="tab-base" href="javascript:;">商品类型</a></li>
                <li><a data-toggle="tab-brands" href="javascript:;">关联品牌</a></li>
                <li><a data-toggle="tab-properties" href="javascript:;">扩展属性</a></li>
                <li><a data-toggle="tab-specifications" href="javascript:;">关联规格</a></li>
            </ul>

            <div class="tab-content">

                <div id="tab-base" class="tab-pane active">
                    <div class="panel-body">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'ordering')->dropDownList(Option::orderingOptions()) ?>

                        <?= $form->field($model, 'status')->checkbox([], false) ?>
                    </div>
                </div>

                <div id="tab-brands" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <?= $form->field($model, 'brandIdList')->checkboxList(Brand::getList(false))->label(false) ?>
                    </div>
                </div>

                <div id="tab-properties" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <div class="grid-view">
                            <table class="table">
                                <thead>
                                <caption>
                                    <a id="btn-add-type-property" href="javascript:;" class="btn">添加属性</a>
                                </caption>
                                <tr>
                                    <th class="type-property-name">属性名称</th>
                                    <th style="type-property-return-type">返回值类型</th>
                                    <th style="type-property-input-method">输入方式</th>
                                    <th class="last">默认值</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if ($model->properties): ?>
                                        <?php foreach ($model->properties as $key => $property): ?>
                                            <tr class="property-<?= $key ?>">
                                                <td>
                                                    <?= Html::checkbox('Type[specificationIdList][]', in_array($property->id, $model->specificationIdList), ['value' => $spec->id]) ?>
                                                </td>
                                                <td><?= $property['name'] ?></td>
                                                <td>
                                                    <?php foreach ($property->values as $value): ?>
                                                        <?= $value['text'] ?>　
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr class="property-0">
                                            <td><?= $form->field($model, 'properties[name][]', ['template' => '{input}'])->textInput(['maxlength' => true]) ?></td>
                                            <td><?= $form->field($model, 'properties[return_type][]', ['template' => '{input}'])->dropDownList(\common\models\TypeProperty::returnTypeOptions()) ?></td>

                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="tab-specifications" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <?php if ($specifications): ?>
                            <div class="grid-view">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="checkbox-column"></th>
                                            <th style="width: 100px;">规格名称</th>
                                            <th class="last">规格值</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($specifications as $spec): ?>
                                            <tr>
                                                <td>
                                                    <?= Html::checkbox('Type[specificationIdList][]', in_array($spec->id, $model->specificationIdList), ['value' => $spec->id]) ?>
                                                </td>
                                                <td><?= $spec['name'] ?></td>
                                                <td>
                                                    <?php foreach ($spec->values as $value): ?>
                                                        <?= $value['text'] ?>　
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="notice">暂无商品规格定义。</div>
                        <?php endif ?>
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
