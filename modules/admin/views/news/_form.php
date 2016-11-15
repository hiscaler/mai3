<?php

use app\models\Category;
use app\models\Label;
use app\models\Lookup;
use app\models\Option;
use yadjet\datePicker\my97\DatePicker;
use yadjet\editor\UEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-layout-column">
    <div class="news-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
        ]);
        $names = [];
        if ($model['entityNodeNames']) {
            foreach ($model['entityNodeNames'] as $name) {
                $names[] = '<em>' . $name . '</em>';
            }
        }
        if ($names) {
            $names = implode('', $names);
        } else {
            $names = '<em class="nothing">' . Yii::t('app', 'No Relationship Nodes.') . '</em>';
        }
        $choiceNodesText = Yii::t('app', 'Choice Nodes');
        $btnChoiceNodes = Html::a($choiceNodesText, ['nodes/choice'], ['class' => 'dialog-choice-nodes button']) . '<span id="node-names">' . $names . '</span>';
        ?>

        <div id="panel-common" class="panel">

            <?= $form->field($model, 'category_id')->dropDownList(Category::getOwnerTree(Lookup::getValue('system.models.category.type.news', 0)), ['prompt' => '']) ?>

            <?= $form->field($model, 'entityNodeIds', [ 'template' => "{label}\n{input}{$btnChoiceNodes}\n{hint}\n{error}",])->hiddenInput() ?>

            <?php
            $entityAttributes = Label::getItems(false, true);
            if ($entityAttributes):
                ?>
                <fieldset>
                    <legend><?= Yii::t('app', 'Entity Labels') ?></legend>
                    <?php
                    foreach ($entityAttributes as $key => $attributes) {
                        echo $form->field($model, 'entityAttributes')->checkboxList($attributes, ['unselect' => null])->label($key);
                    }
                    ?>
                </fieldset>
            <?php endif; ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

            <?= $form->field($model, 'short_title')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

            <?= $form->field($model, 'tags')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

            <?= $form->field($model, 'keywords')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

            <?=
            $form->field($model, 'author', [
                'template' => "{label}\n{input} <a data-key=\"news-author\" class=\"btn-choice-lookup\" href=\"" . Url::to(['choice-lookup', 'label' => 'model.news.author']) . "\">" . Yii::t('app', 'Choice') . "</a>\n{hint}\n{error}"
            ])->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large'])
            ?>

            <?=
            $form->field($model, 'source', [
                'template' => "{label}\n{input} <a data-key=\"news-source\" class=\"btn-choice-lookup\" href=\"" . Url::to(['choice-lookup', 'label' => 'model.news.source']) . "\">" . Yii::t('app', 'Choice') . "</a>\n{hint}\n{error}"
            ])->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large'])
            ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?=
            UEditor::widget([
                'form' => $form,
                'model' => $newsContent,
                'attribute' => 'content',
            ])
            ?>

            <div class="entry">
                <?php
                $imagePreview = $imageCropperHtml = null;
                $imagePath = $model->picture_path;
                if (!$model->isNewRecord && !empty($imagePath)) {
                    if ($model->_fileUploadConfig['thumb']['generate']) {
                        $linkOptions = [
                            'width' => $model->_fileUploadConfig['thumb']['width'],
                            'height' => $model->_fileUploadConfig['thumb']['height']
                        ];
                    } else {
                        $linkOptions = [];
                    }
                    $imagePath = Yii::$app->getRequest()->getBaseUrl() . $imagePath;
                    $imagePreview = Html::a(Html::img($imagePath, $linkOptions), $imagePath, ['target' => '_blank']);
                    $imagePreview .= Html::a('x', ['remove-image', 'id' => $model->id], ['class' => 'btn-remove']);
                    $imagePreview = Html::tag('div', $imagePreview, ['class' => 'image-preview']);
                    $imageCropperHtml = Html::a(Yii::t('app', 'Image Cropper'), ['image-service/crop', 'filename' => $imagePath], ['class' => 'open-image-cropper-dialog']);
                }
                echo $form->field($model, 'content_image_number')->dropDownList(Option::orderingOptions(1, 20), ['prompt' => '']);
                echo $form->field($model, 'picture_path', [
                    'template' => "{label}\n{input}{$imageCropperHtml}{$imagePreview}\n{hint}\n{error}"
                ])->fileInput()
                ?>
            </div>

            <div class="entry">
                <?=
                DatePicker::widget([
                    'form' => $form,
                    'model' => $model,
                    'attribute' => 'published_at',
                    'pickerType' => 'datetime',
                ]);
                ?>

                <?= $form->field($model, 'enabled_comment')->checkbox([], false) ?>
            </div>

            <div class="entry">
                <?= $form->field($model, 'clicks_count')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-number']) ?>

                <?= $form->field($model, 'ordering')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-number']) ?>
            </div>

            <?php if ($model->isNewRecord || $model->isDraft): ?>
                <div class="entry">
                    <?= $form->field($model, 'isDraft')->checkbox([], false) ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$js = <<<EOT
jQuery(document).on('click', 'a.dialog-choice-nodes', function () {
    $.ajax({
        type: 'GET',
        url: $(this).attr('href'),
        data: {
            nodeIds: $('#news-entitynodeids').val()
        },
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            $.dialog({
                'id': 'nodes-list',
                title: '{$choiceNodesText}',
                content: response,
                lock: true,
                padding: '10px',
                ok: function () {
                    var choiceObject = $.fn.zTree.getZTreeObj("__ztree__").getCheckedNodes(true);
                    var ids = [], names = [];
                    $.each(choiceObject, function(i, o) {
                        ids.push(o.id);
                        names.push('<em>' + o.name + '</em>');
                    });
                    $('#news-entitynodeids').val(ids.toString());
                    $('#node-names').html(names.join(''));
                }
            });
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });

    return false;
});
EOT;
$this->registerJs($js);

$js = <<<'EOT'
jQuery(document).on('click', 'a.btn-remove', function () {
    var $this = $(this);
    $.ajax({
        type: 'POST',
        url: $this.attr('href'),
        dataType: 'json',
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            if (response.success) {
                $('.image-preview').remove();
            }
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });

    return false;
});
EOT;
$this->registerJs($js);

// 资讯来源和作者
$js = <<<'EOT'
jQuery(document).on('click', 'a.btn-choice-lookup', function () {
    var $t = $(this);
    var key = $t.attr('data-key');
    $.ajax({
        type: 'GET',
        url: $t.attr('href'),
        data: { current: $('#' + key).val() },
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            $.dialog({
                'id': 'choice-lookup-value',
                content: response,
                lock: true,
                padding: '10px',
                ok: function () {
                    var text = $('input[name = "__lookup_value__"]:checked').val();
                    if (text != undefined && text != '') {
                        $('#' + key).val(text);
                    }
                }
            });
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });

    return false;
});
EOT;
$this->registerJs($js);
