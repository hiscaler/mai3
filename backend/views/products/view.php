<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>

<div class="tabs-container">
    <ul class="tabs-common">
        <li class="active"><a data-toggle="tab-base" href="#tab-base"> 基础资料</a></li>
        <li><a data-toggle="tab-content" href="#tab-content">详情描述</a></li>
        <li><a data-toggle="tab-images" href="#tab-images">商品图片</a></li>
    </ul>

    <div class="tab-content">

        <!-- 基础资料 -->
        <div id="tab-base" class="tab-pane active">
            <div class="panel-body">
                <div class="item-view">

                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'ordering',
                            'sn',
                            'name',
                            'category.name',
                            'brand.name',
                            'market_price',
                            'shop_price',
                            'member_price',
                            'picture_path',
                            'keywords',
                            'description:ntext',
                            'clicks_count',
                            'sales_count',
                            'status',
                            'created_at:datetime',
                            'created_by',
                            'updated_at:datetime',
                            'updated_by',
                        ],
                    ])
                    ?>

                </div>
            </div>
        </div>
        <!-- // 基础资料 -->

        <!-- 详情描述 -->
        <div id="tab-content" class="tab-pane" style="display: none;">
            <div class="panel-body">
                <?= $model['content'] ?>
            </div>
        </div>
        <!-- // 详情描述 -->

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
            </div>
        </div>
        <!-- // 商品图片 -->

    </div>
</div>

