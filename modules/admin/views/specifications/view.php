<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Specification */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Specifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>


<div class="tabs-container">
    <ul class="tabs-common">
        <li class="active"><a data-toggle="tab-base" href="#tab-base">规格详情</a></li>
        <li><a data-toggle="tab-values" href="#tab-values">规格值</a></li>
    </ul>

    <div class="tab-content">
        <!-- 规格详情 -->
        <div id="tab-base" class="tab-pane active">

            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'ordering',
                    'name',
                    'type_text',
                    'enabled:boolean',
                    'created_at:datetime',
                    'updated_by:datetime',
                ],
            ])
            ?>

        </div>
        <!-- // 规格详情 -->

        <!-- 规格值 -->
        <div id="tab-values" class="tab-pane" style="display: none;">
            <div class="grid-view">
                <table class="table">
                    <thead>
                        <tr>
                            <th>排序</th>
                            <th>规格值名称</th>
                            <th>图标</th>
                            <th class="last">激活</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $baseUrl = Yii::$app->getRequest()->getBaseUrl();
                        $formatter = Yii::$app->getFormatter();
                        foreach ($model['values'] as $value):
                            ?>
                            <tr>
                                <td class="ordering"><?= $value['ordering'] ?></td>
                                <td><?= $value['text'] ?></td>
                                <td><?= $baseUrl . $value['icon_path'] ?></td>
                                <td class="boolean"><?= $formatter->asBoolean($value['enabled']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- // 规格值 -->

    </div>
</div>
