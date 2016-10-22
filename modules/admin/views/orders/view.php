<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->sn;
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Order'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>

<div>
    <ul class="tabs-common">
        <li class="active"><a href="javascript:;" data-toggle="tab-base">基本信息</a></li>
        <li><a href="javascript:;" data-toggle="tab-details">订单详情</a></li>
    </ul>

    <div class="panels">
        <div class="tab-pane" id="tab-base">
            <div class="order-view">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'sn',
                        'quantity',
                        'total_amount',
                        'discount_amount',
                        'express_fee',
                        'actual_amount',
                        'address.linkman',
                        'address.tel',
                        'address.address',
                        'express_id',
                        'express_sn',
                        'status_text',
                        'remark',
                        'created_at:datetime',
                        'creater.username',
                    ],
                ])
                ?>

            </div>
        </div>

        <div class="tab-pane" id="tab-details" style="display: none;">
            <div class="grid-view">
                <table class="table">
                    <thead>
                        <tr>
                            <th>序号</th>
                            <th>商品</th>
                            <th>数量</th>
                            <th>单价</th>
                            <th>优惠</th>
                            <th>运费</th>
                            <th>小计</th>
                            <th>状态</th>
                            <th class="last">备注</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model['details'] as $i => $detail): ?>
                            <tr>
                                <td class="serial-number"><?= $i + 1 ?></td>
                                <td><?= $detail['item']['name'] ?></td>
                                <td class="quantity"><?= $detail['quantity'] ?></td>
                                <td class="price"><?= $detail['price'] ?></td>
                                <td class="amount"><?= $detail['discount_amount'] ?></td>
                                <td class="amount"><?= $detail['express_fee'] ?></td>
                                <td class="amount"><?= $detail['subtotal_amount'] ?></td>
                                <td class="order-status"><?= $detail['status'] ?></td>
                                <td class="amount"><?= $detail['remark'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

