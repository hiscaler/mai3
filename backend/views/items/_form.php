<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?php
                $form = ActiveForm::begin([
                        'options' => [
                            'id' => 'form-item',
                            'enctype' => 'multipart/form-data'
                        ],
                ]);
                ?>
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-base"> 基础资料</a></li>
                        <li><a data-toggle="tab" href="#tab-content">详情描述</a></li>
                        <li><a data-toggle="tab" href="#tab-images">商品图片</a></li>
                    </ul>

                    <div class="tab-content">

                        <div id="tab-base" class="tab-pane active">
                            <div class="panel-body">
                                <?= $form->field($model, 'category_id')->dropDownList(common\models\Category::getMap(), ['prompt' => '']) ?>

                                <?= $form->field($model, 'brand_id')->dropDownList(\common\models\Brand::getMap(), ['prompt' => '']) ?>

                                <?= $form->field($model, 'sn')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'market_price')->textInput() ?>

                                <?= $form->field($model, 'shop_price')->textInput() ?>

                                <?= $form->field($model, 'member_price')->textInput() ?>

                                <?= $form->field($model, 'picture_path')->fileInput() ?>

                                <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                                <?= $form->field($model, 'ordering')->textInput() ?>

                                <?= $form->field($model, 'clicks_count')->textInput() ?>

                                <?= $form->field($model, 'sales_count')->textInput() ?>

                                <?= $form->field($model, 'status')->checkbox() ?>
                            </div>
                        </div>

                        <!-- 详细描述 -->
                        <div id="tab-content" class="tab-pane">
                            <div class="panel-body">
                                <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
                            </div>
                        </div>
                        <!-- // 详细描述 -->

                        <!-- 商品图片 -->
                        <div id="tab-images" class="tab-pane">
                            <div class="panel-body">
                                <?php if ($model->images): ?>
                                    <div class="well">
                                        <ul class="item-images clearfix">
                                            <?php foreach ($model->images as $file): ?>
                                                <li>
                                                    <div class="image">
                                                        <img src="<?= $file->path ?>" />
                                                        <span class="btn-delete-image glyphicon glyphicon-trash" data-key="<?= $file->id ?>" data-url="<?= yii\helpers\Url::toRoute(['delete-image', 'id' => $file->id]) ?>"></span>
                                                    </div>
                                                    <div class="description">
                                                        <input class="update-image-description" type="text" data-key="<?= $file->id ?>" data-original="<?= $file->description ?>" data-url="<?= yii\helpers\Url::toRoute(['update-image-description', 'id' => $file->id]) ?>" value="<?= $file->description ?>">
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                    </div>
                                <?php endif ?>

                                <div id="grid-goods-images" class="grid-view well">                            
                                    <table class="table remove-margin-bottom-value">
                                        <caption>
                                            <a id="btn-add-new-goods-image-row" href="javascript:;" class="btn btn-primary btn-xs">添加一行</a>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="width: 120px">上传文件</th>
                                                <th>外部文件</th>
                                                <th>图片描述</th>
                                                <th class="btns-1 last"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="row-0">
                                                <td><input name="Item[imageFiles][]" type="file"></td>
                                                <td><input name="Item[imageFiles][url][]" class="form-control" type="text" placeholder="外部图片地址（http 开头）"></td>
                                                <td><input name="Item[imageFiles][description][]" class="form-control" type="text" placeholder="图片简短描述文字"></td>
                                                <td class="btns"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- // 商品图片 -->                       

                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>


                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
