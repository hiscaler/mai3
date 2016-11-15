<?php if ($values): ?>
    <div class="inline-box clearfix">
        <?= yii\helpers\Html::radioList('__lookup_value__', $current, array_combine($values, $values)) ?>
    </div>
<?php else: ?>
    <div class="notice">
        关联数据尚未设置。
    </div>
<?php endif ?>
