<?php

use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column" style="display: none">
    <div class="news-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-news-search',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'id') ?>

            <?= $form->field($model, 'title') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'category_id')->dropDownList(\app\models\Category::getOwnerTree(\app\models\Lookup::getValue('system.models.category.type.news', 0)), ['prompt' => '', 'multiple' => 'multiple']) ?>

            <?= $form->field($model, 'entityLabelId')->dropDownList(\app\models\Label::getItems(true), ['prompt' => '', 'multiple' => 'multiple'])->label(Yii::t('app', 'Entity Labels')) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'author') ?>

            <?= $form->field($model, 'source') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'status')->dropDownList(Option::statusOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/admin';
$this->registerJsFile($baseUrl . '/chosen/chosen.jquery.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerCssFile($baseUrl . '/chosen/chosen.min.css');
$js = <<<EOT
$('#newssearch-category_id, #newssearch-entitylabelid').chosen({
    no_results_text: '无匹配节点：',
    placeholder_text_multiple: '点击此处，在空白框内输入或选择节点名称',
    width: '60%',
    search_contains: true,
    allow_single_deselect: true
});
EOT;
$this->registerJs($js);
