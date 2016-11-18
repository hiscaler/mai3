<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="tenant-form form">
        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'id' => 'form-item',
                    'enctype' => 'multipart/form-data'
                ],
        ]);
        ?>
        <?= $form->errorSummary($model) ?>
        <div class="tabs-container">
            <ul class="tabs-common">
                <li class="active"><a data-toggle="tab-base" href="#tab-base"> 基础资料</a></li>
                <li><a data-toggle="tab-content" href="#tab-content">详情描述</a></li>
                <li><a data-toggle="tab-images" href="#tab-images">商品图片</a></li>
                <li><a data-toggle="tab-specifications" href="#tab-specifications">商品规格</a></li>
                <li><a data-toggle="tab-properties" href="#tab-properties">商品属性</a></li>
            </ul>

            <div class="tab-content">

                <div id="tab-base" class="tab-pane active">
                    <div class="panel-body">
                        <div class="entry">
                            <?= $form->field($model, 'category_id')->dropDownList(app\models\Category::getTree(\app\models\Lookup::getValue('system.models.category.type.product', 0)), ['prompt' => '']) ?>

                            <?= $form->field($model, 'type_id')->dropDownList(\app\models\Type::getList(), array_merge(['prompt' => '', 'data-url' => yii\helpers\Url::toRoute(['type-raw-data'])], $model->isNewRecord ? [] : ['disabled' => 'disabled'])) ?>
                        </div>

                        <div class="entry">
                            <?= $form->field($model, 'brand_id')->dropDownList(\app\models\Brand::getList(), ['prompt' => '']) ?>

                            <?= $form->field($model, 'sn')->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="entry">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'market_price')->textInput() ?>
                        </div>

                        <div class="entry">
                            <?= $form->field($model, 'shop_price')->textInput() ?>

                            <?= $form->field($model, 'member_price')->textInput() ?>
                        </div>

                        <div class="entry">
                            <?= $form->field($model, 'picture_path')->fileInput() ?>

                            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
                        </div>

                        <!-- 扩展资料 -->
                        <?php
                        foreach ($metaItems as $key => $item) {
                            echo $form->field($dynamicModel, $key)->{$item['input_type']}($item['input_candidate_value'])->label($item['label'])->hint($item['description']);
                        }
                        ?>
                        <!-- // 扩展资料 -->

                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                        <div class="entry">
                            <?= $form->field($model, 'online')->checkbox([], false) ?>

                            <?= $form->field($model, 'status')->checkbox([], false) ?>
                        </div>

                        <div class="entry">
                            <?= $form->field($model, 'view_require_credits')->textInput() ?>

                            <?= $form->field($model, 'ordering')->textInput() ?>
                        </div>

                        <div class="entry">
                            <?= $form->field($model, 'privilegeUsers')->checkboxList(app\models\Member::getListByUserGroup('vip')) ?>
                        </div>

                    </div>
                </div>

                <!-- 详细描述 -->
                <div id="tab-content" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <?=
                        \yadjet\editor\UEditor::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute' => 'content',
                        ])
                        ?>
                    </div>
                </div>
                <!-- // 详细描述 -->

                <!-- 商品图片 -->
                <div id="tab-images" class="tab-pane" style="display: none;">
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
                                    <a id="btn-add-new-goods-image-row" href="javascript:;" class="btn-circle">+</a>
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
                                        <td><input name="Product[imageFiles][]" type="file"></td>
                                        <td><input name="Product[imageFiles][url][]" class="form-control" type="text" placeholder="外部图片地址（http 开头）"></td>
                                        <td><input name="Product[imageFiles][description][]" class="form-control" type="text" placeholder="图片简短描述文字"></td>
                                        <td class="btns"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- // 商品图片 -->

                <!-- 商品规格 -->
                <div id="tab-specifications" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <div id="mai3-item-specifications">
                            <div class="specifications-values" v-if="specifications.length > 0">
                                <fieldset v-for="spec in specifications" v-bind:class="{active: $index === 0}">
                                    <legend>{{ spec.name }}</legend>
                                    <div class="list">
                                        <template v-for="value in spec.values">
                                            <span>
                                                <input v-on:click="checkSpecificationValue" type="checkbox" id="specification-value-{{ value.id }}" name="specificationValues[]" value="{{ value.id }}" checked="{{ value.checked }}" data-specification="{{ spec.id }}">
                                                <label id="label-{{ value.id }}" for="specification-value-{{ value.id }}">{{ value.value }}</label>
                                            </span>
                                        </template>
                                    </div>
                                </fieldset>
                                <div class="grid-view" id="mai3-item-specification-values-combination-render">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>属性值</th>
                                                <th class="item-sn">品号</th>
                                                <th>品名</th>
                                                <th class="price">市场价</th>
                                                <th class="price">商城价</th>
                                                <th class="price">会员价</th>
                                                <th class="button-1">默认</th>
                                                <th class="last button-1">上架</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in items" v-bind:class="{'disabled': !item.enabled, 'enabled': item.enabled, 'new': item._isNew}">
                                                <td class="item-specification-values">
                                                    {{ item.text }}
                                                    <input type="hidden" name="Product[skuItems][id][]" value="{{ item.id }}" />
                                                    <input type="hidden" name="Product[skuItems][specification_value_ids][]" value="{{ item.specificationValueString }}" />
                                                </td>
                                                <td v-bind:class="{'error': item._snError}"><input class="sn" type="text" name="Product[skuItems][sn][]" value="{{ item.sn }}" /></td>
                                                <td><input class="name" type="text" name="Product[skuItems][name][]" value="{{ item.name }}" /></td>
                                                <td><input class="price" type="text" name="Product[skuItems][market_price][]" value="{{ item.price.market }}" /></td>
                                                <td><input class="price" type="text" name="Product[skuItems][shop_price][]" value="{{ item.price.shop }}" /></td>
                                                <td><input class="price" type="text" name="Product[skuItems][member_price][]" value="{{ item.price.member }}" /></td>
                                                <td><input type="radio" name="Product[skuItems][default][]" v-model="item._default_id" value="{{ item.id }}" /></td>
                                                <td><input type="checkbox" name="Product[skuItems][online][]" v-model="item.online" value="{{ $index }}" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div v-else>
                                <div class="alert alert-info">暂未设定相关分类属性。</div>
                            </div>
                        </div>
                    </div>
                    <!-- // 商品规格 -->
                </div>

                <!-- 商品属性 -->
                <div id="tab-properties" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <div class="form-group" v-for="item in product.properties">
                            <label class="control-label" for="product-type-property-{{ item.id }}">{{ item.name }}</label>
                            <input type="hidden" name="Product[propertyItems][id][]" v-model="item.id" />
                            <input v-if="item.input_method == 0" type="text" name="Product[propertyItems][value][]" v-model="item.value" />
                            <select v-if="item.input_method == 2" name="Product[propertyItems][value][]" v-model="item.value">
                                <option v-bind:value="v" v-for="v in item.input_values">{{ v }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- // 商品属性 -->

            </div>
            <div class="form-group buttons">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php \app\modules\admin\components\JsBlock::begin() ?>
<script type="text/javascript">
    var url = '<?= \yii\helpers\Url::toRoute(['api/type', 'id' => $model['type_id'], 'productId' => $model['id']]) ?>';
    Vue.http.get(url).then((res) => {
    vm.original = res.data;
    vm.specifications = _.toArray(res.data.specifications);
    vm._items = _.toArray(res.data.items);
    vm.items = vm._items;
    vm.rawSpecificationValues = res.data.checkedSpecificationValues;
    });
<?php if (!$model->isNewRecord): ?>
        url = '<?= \yii\helpers\Url::toRoute(['api/product-properties', 'productId' => $model['id'], 'typeId' => $model['type_id']]) ?>';
        Vue.http.get(url).then((res) => {
        vm.product.properties = res.data;
        });
        Mai3.reference.product = {
        name: '<?= $model->name ?>',
            snPrefix: '<?= $model->sn ?>',
            price: {
            member: <?= $model->member_price ?>,
                market: <?= $model->market_price ?>
            }
        };
<?php endif; ?>
</script>
<?php \app\modules\admin\components\JsBlock::end() ?>
