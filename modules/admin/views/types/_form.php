<?php

use app\models\Brand;
use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Type */
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

                        <?= $form->field($model, 'ordering')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'enabled')->checkbox([], false) ?>
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
                                    <a id="btn-add-type-property" href="<?= yii\helpers\Url::toRoute(['create-property', 'typeId' => $model['id']]) ?>" class="btn-circle">+</a>
                                </caption>
                                <tr>
                                    <th class="type-property-name">属性名称</th>
                                    <th style="type-property-return-type">返回值类型</th>
                                    <th style="type-property-input-method">输入方式</th>
                                    <th class="last">默认值</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in type.properties">
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.return_type }}</td>
                                        <td>{{ item.input_method }}</td>
                                        <td>{{ item.input_default_value }}</td>
                                    </tr>
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
                                            <th style="width: 100px;">规格名称</th>
                                            <th class="last">规格值</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($specifications as $spec): ?>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <?= Html::checkbox('Type[specificationIdList][]', in_array($spec->id, $model->specificationIdList), ['value' => $spec->id]) ?>
                                                        <?= $spec['name'] ?>
                                                    </label>
                                                </td>
                                                <td class="wrap">
                                                    <?php foreach ($spec->values as $value): ?>
                                                        <label class="circle"><?= $value['text'] ?></label>
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

<?php \app\modules\admin\components\JsBlock::begin() ?>
<script type="text/javascript">
    var url = '<?= \yii\helpers\Url::toRoute(['api/type-properties', 'typeId' => $model['id']]) ?>';
    Vue.http.get(url).then((res) => {
        vm.type.properties = res.data;
    });
</script>
<?php \app\modules\admin\components\JsBlock::end() ?>
