<?php

use app\models\User;
use app\models\Yad;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this View */

$this->title = Yii::$app->name;

$isAdministrator = true;
?>

<div id="tenants-list" class="message-box">
    <div class="hd">
        <?= $tenants ? '请选择您要管理的站点' : '提示信息' ?>
        <?php
        if ($isAdministrator) {
            echo Html::tag('em', Html::a('+', ['/admin/tenants/create'], ['class' => 'btn']));
        }
        ?>
    </div>
    <div class="bd">
        <?php if ($tenants): ?>
            <ul class="clearfix">
                <?php foreach ($tenants as $tenant): ?>
                    <li>
                        <div class="base">
                            <a href="<?= Url::to(['default/change-tenant', 'tenantId' => $tenant['id']]) ?>">
                                <span class="name">
                                    <em><?= $tenant['domain_name'] ?></em>
                                    <?= $tenant['name'] ?>
                                </span>
                                <?php if ($tenant['description']): ?>
                                    <span class="description"><?= $tenant['description'] ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <?php
                        $actionOutput = Html::a('访问站点', 'http://' . $tenant['domain_name'], [
                                'class' => 'first' . ($isAdministrator ? '' : ' last'),
                                'target' => '_blank',
                        ]);
                        if ($isAdministrator) {
                            $actionOutput .= Html::a('管理', ['tenants/update', 'id' => $tenant['id']]);
                            $actionOutput .= Html::a('停止', ['tenants/toggle'], [
                                    'class' => 'btn-disable-tenant last',
                                    'data-key' => $tenant['id']
                            ]);
                        }
                        echo Html::tag('div', $actionOutput, ['class' => 'actions']);
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            暂无需要管理的站点，请联系系统管理员。
        <?php endif; ?>
    </div>
</div>

<?php
$js = <<<'EOT'
   jQuery(document).on('click', 'a.btn-disable-tenant', function () {
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            dataType: 'json',
            data: { id: $this.attr('data-key') },
            beforeSend: function (xhr) {
                $.fn.lock();
            }, success: function (response) {
                if (response.success) {
                    var data = response.data;
                    var texts = ['关闭', '开启'];
                    $this.val(texts[data.value ? 1 : 0]);
                } else {
                    $.alert(response.error.message);
                }
                $.fn.unlock();
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
                $.fn.unlock();
            }
        });
        
return false;
    });
EOT;
$this->registerJs($js);

