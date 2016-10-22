<?php
$this->params['menus'] = [
    ['label' => '店铺管理', 'url' => ['index']],
];

$formatter = \Yii::$app->getFormatter();
?>

<div class="lasted-members wrapper">
    <div class="grid-view">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>用户名</th>
                    <th>E-mail</th>
                    <th class="last">注册时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $item): ?>
                    <tr>
                        <td class="username"><a href="<?= yii\helpers\Url::toRoute(['members/view', 'id' => $item['id']]) ?>"><?= $item['username'] ?></a></td>
                        <td><?= $item['email'] ?></td>
                        <td class="datetime"><?= $formatter->asDatetime($item['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="lasted-orders">
    <div class="grid-view">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>金额</th>
                    <th class="last">下单时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $item): ?>
                    <tr>
                        <td><a href="<?= yii\helpers\Url::toRoute(['orders/view', 'id' => $item['id']]) ?>"><?= $item['sn'] ?></a></td>
                        <td class="number"><?= $item['total_amount'] ?></td>
                        <td class="datetime"><?= $formatter->asDatetime($item['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
