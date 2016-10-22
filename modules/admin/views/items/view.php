<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>

<div class="tabs-container">
    <ul class="tabs-common">
        <li class="active"><a data-toggle="tab-base" href="#tab-base">单品详情</a></li>
        <li><a data-toggle="tab-content" href="#tab-content">详情描述</a></li>
        <li><a data-toggle="tab-orders" href="#tab-items">销售记录<em class="badges badges-red"><?= count($model['sales']) ?></em></a></li>
        <li><a data-toggle="tab-comments" href="#tab-images">评论<em class="badges badges-red"><?= count($model['comments']) ?></em></a></li>
    </ul>

    <div class="tab-content">
        <!-- 单品详情 -->
        <div id="tab-base" class="tab-pane active">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'product.name',
                    'sn',
                    'name',
                    'market_price',
                    'shop_price',
                    'member_price',
                    'cost_price',
                    'picture_path:image',
                    'clicks_count',
                    'favorites_count',
                    'sales_count',
                    'stocks_count',
                    'default:boolean',
                    'enabled:boolean',
                    'view_require_credits',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ])
            ?>

        </div>
        <!-- // 单品详情 -->

        <!-- 详情描述 -->
        <div id="tab-content" class="tab-pane" style="display: none;">
            <?= $model['product']['content'] ?>
        </div>
        <!-- // 详情描述 -->

        <!-- 订单 -->
        <div id="tab-orders" class="tab-pane" style="display: nonec;">
            <div class="grid-view">
                <table class="table">
                    <thead>
                        <tr>
                            <th>数量</th>
                            <th>价格</th>
                            <th>优惠金额</th>
                            <th>合计</th>
                            <th class="last">备注</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model->sales as $i => $sale): ?>
                            <tr class="<?= $i % 2 == 0 ? 'odd' : 'even' ?>">
                                <td class="number"><?= $sale['quantity'] ?></td>
                                <td class="price"><?= $sale['price'] ?></td>
                                <td class="amount"><?= $sale['discount_amount'] ?></td>
                                <td class="amount"><?= $sale['subtotal_amount'] ?></td>
                                <td><?= $sale['remark'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- // 订单 -->


        <!-- 评论 -->
        <div id="tab-comments" class="tab-pane" style="display: none;">
            <div class="grid-view">
                <table class="table">
                    <thead>
                        <tr>
                            <th>评论人</th>
                            <th>电话</th>
                            <th>邮箱</th>
                            <th>内容</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model['comments'] as $item): ?>
                            <tr>
                                <td class="username"><?= $item['username'] ?></td>
                                <td class="tel"><?= $item['tel'] ?></td>
                                <td class="email"><?= $item['email'] ?></td>
                                <td><?= $item['message'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- // 评论 -->
    </div> 
</div>
