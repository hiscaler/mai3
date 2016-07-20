<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
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
        <div class="tabs-container">
            <ul class="tabs-common">
                <li class="active"><a data-toggle="tab-base" href="#tab-base"> 基础资料</a></li>
                <li><a data-toggle="tab-content" href="#tab-content">详情描述</a></li>
                <li><a data-toggle="tab-images" href="#tab-images">商品图片</a></li>
                <li><a data-toggle="tab-specifications" href="#tab-specifications">商品规格</a></li>
            </ul>

            <div class="tab-content">

                <div id="tab-base" class="tab-pane active">
                    <div class="panel-body">
                        <?= $form->field($model, 'category_id')->dropDownList(common\models\Category::getMap(), ['prompt' => '']) ?>

                        <?= $form->field($model, 'type_id')->dropDownList(\common\models\Type::getMap(), ['prompt' => '', 'data-url' => yii\helpers\Url::toRoute(['type-raw-data'])]) ?>

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
                <div id="tab-content" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
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

                <!-- 商品规格 -->
                <div id="tab-specifications" class="tab-pane" style="display: none;">
                    <div class="panel-body">
                        <div id="mai3-item-specifications">
                            <div v-if="specifications.length > 0">                                    
                                <ul class="specifications-values">
                                    <li v-for="spec in specifications" v-bind:class="{active: $index === 0}">
                                        <em>{{ spec.name }}</em>
                                        <div class="list">
                                            <template v-for="value in spec.values">
                                                <span>
                                                    <input v-on:click="checkSpecificationValue" type="checkbox" id="specification-value-{{ value.id }}" name="specificationValues[]" value="{{ value.id }}">
                                                    <label id="label-{{ value.id }}" for="specification-value-{{ value.id }}">{{ value.value }}</label>
                                                </span>
                                            </template>
                                        </div>
                                    </li>
                                </ul>
                                <div class="grid-view" id="mai3-item-specification-values-combination-render">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>属性值</th>
                                                <th class="item-sn">品号</th>
                                                <th>品名</th>
                                                <th class="price">市场价</th>
                                                <th class="price">会员价</th>
                                                <th class="button-1">默认</th>
                                                <th class="last button-1">状态</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in specificationValueCombinationList">
                                                <td>
                                                    {{ item.text }}
                                                    <input type="hidden" name="Item[skuItems][specification_value_ids][]" value="{{ item.id }}" />
                                                </td>
                                                <td><input class="sn" type="text" name="Item[skuItems][sn][]" value="{{ item.sn }}" /></td>
                                                <td><input class="name" type="text" name="Item[skuItems][name][]" value="{{ item.name }}" /></td>
                                                <td><input class="price" type="text" name="Item[skuItems][market_price][]" value="{{ item.price.market }}" /></td>
                                                <td><input class="price" type="text" name="Item[skuItems][member_price][]" value="{{ item.price.member }}" /></td>
                                                <td><input type="radio" name="Item[skuItems][default][]" value="1" /></td>
                                                <td><input type="checkbox" name="Item[skuItems][enabled][]" value="1" /></td>
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
            </div>
            <div class="form-group buttons">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
数据:<input name="s" size="30" id="s" value="[1,2,3,4,5]">
取值量:<input id="n" name="n" size="2" value="3"/>
<button onclick="com()">计算</button>
<div id="r"></div>
<?php backend\components\JsBlock::begin() ?>
<script type="text/javascript">
    var url = '<?= \yii\helpers\Url::toRoute(['api/type', 'id' => $model['id']]) ?>';
        Vue.http.get(url).then((res) => {
        vm.original = res.data;
        vm.specifications = _.toArray(res.data.specifications);
    });
    function com(){
    var s = eval(document.getElementById('s').value);
    var n = document.getElementById('n').value;
    var r = document.getElementById('r');
    r.innerHTML = "";
                for (var i=0;i<Math.pow(2, s.length);i++){
    var a = 0;
    var b = "";
                    for (var j=0;j<s.length;j++){
                        if (i>>j&1){
    a++;
    b += s[j];
    }
    }
                    if (a==n){
                        r.innerHTML += (b+"<br/>");
    }
    }
    }

    var res = [];
    if (vm.specifications.length > 0) {
    console.info('fff');
    } else {
    console.info('d');
    }

      var specArr = [{"specid":"20111201180241703974","specname":"颜色","showtype":1,"showway":0,"selvalues":[{"valueid":"1","valtext":"黑色","specimg":"/_B/2011-12-01/2159112965553.gif","ctmtext":"黑色","ctmimg":"/_B/2011-12-01/2159112965553.gif","productpics":[]},{"valueid":"6","valtext":"黄色","specimg":"/_B/2011-12-01/2217398598572.gif","ctmtext":"黄色","ctmimg":"/_B/2011-12-01/2217398598572.gif","productpics":[]}]},{"specid":"20111201185422843990","specname":"尺码","showtype":0,"showway":1,"selvalues":[{"valueid":"7","valtext":"均码","specimg":"","ctmtext":"均码","ctmimg":"","productpics":[]},{"valueid":"8","valtext":"XXS","specimg":"","ctmtext":"XXS","ctmimg":"","productpics":[]}]},{"specid":"20111201180241703999","specname":"产地","showtype":1,"showway":0,"selvalues":[{"valueid":"1001","valtext":"美国","specimg":"/_B/2011-12-01/2159112965553.gif","ctmtext":"美国","ctmimg":"/_B/2011-12-01/2159112965553.gif","productpics":[]},{"valueid":"1002","valtext":"法国","specimg":"/_B/2011-12-01/2217398598572.gif","ctmtext":"法国","ctmimg":"/_B/2011-12-01/2217398598572.gif","productpics":[]},{"valueid":"1003","valtext":"英国","specimg":"/_B/2011-12-01/2159112965553.gif","ctmtext":"英国","ctmimg":"/_B/2011-12-01/2159112965553.gif","productpics":[]}]}];
  
    var arrResult = new Array();
    console.info(specArr[0]);
  for(var z=0; z<specArr[0].selvalues.length; z++) {
    arrResult[arrResult.length] = specArr[0].selvalues[z].valtext;
    }

  for(var i=1; i<specArr.length; i++) {
    arrResult = CombineArray(arrResult, specArr[i].selvalues);
    }

    function CombineArray(arr1, arr2) {
    var arrResultSub = new Array();
    for(var i=0; i<arr1.length; i++) {
        for(var k=0; k<arr2.length; k++) {
    arrResultSub[arrResultSub.length] = arr1[i] + "," + arr2[k].valtext;
    }
    }
    return arrResultSub;
    }

    for (var j = 0; j < arrResult.length; j++) {
    document.writeln(arrResult[j]);
    }
<?php if (!$model->isNewRecord): ?>
        Mai.reference.item = {
            name: '<?= $model->name ?>',
            snPrefix: '<?= $model->sn ?>',
            price: {
                member: <?= $model->member_price ?>,
                market: <?= $model->market_price ?>
            }
        };
<?php endif; ?>
</script>
<?php backend\components\JsBlock::end() ?>
