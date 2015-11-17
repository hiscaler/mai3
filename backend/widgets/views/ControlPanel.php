<?php

use common\models\Yad;
use yii\helpers\Url;
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <img alt="<?= $identity->username ?>" class="img-circle" src="<?= Yii::$app->getRequest()->getBaseUrl() ?>/images/profile_small.jpg" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?= $identity->username ?></strong>
                            </span> <span class="text-muted text-xs block"><?= Yad::getTenantId() ? Yad::getTenantName() : '站点切换' ?> <b class="caret"></b></span> </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <?php foreach ($tenants as $key => $value): ?>
                            <li><a href="<?= Url::toRoute(['/default/change-tenant', 'id' => $key]) ?>"><?= $value ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="logo-element">
                    Mai3
                </div>
            </li>
            <li<?= in_array($controllerId, ['tenants', 'users']) ? ' class="active"' : '' ?>>
                <a href="javascript:;"><i class="fa fa-tv"></i> <span class="nav-label">系统管理</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li<?= $controllerId == 'tenants' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['tenants/index']) ?>">站点管理</a></li>
                    <li<?= $controllerId == 'users' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['users/index']) ?>">系统用户管理</a></li>
                </ul>
            </li>
            <li<?= in_array($controllerId, ['lookups', 'labels']) ? ' class="active"' : '' ?>>
                <a href="javascript:;"><i class="fa fa-cog"></i> <span class="nav-label">站点管理</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?= Url::toRoute(['lookups/index']) ?>">系统设置</a></li>
                    <li<?= $controllerId == 'labels' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['labels/index']) ?>">推送位管理</a></li>
                    <li<?= $controllerId == 'members' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['members/index']) ?>">会员管理</a></li>
                </ul>
            </li>
            <li<?= in_array($controllerId, ['ads', 'news', 'articles']) ? ' class="active"' : '' ?>>
                <a href="javascript:;"><i class="fa fa-sitemap"></i> <span class="nav-label">内容管理</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?= Url::toRoute(['ads/index']) ?>">广告管理</a></li>
                    <li><a href="<?= Url::toRoute(['news/index']) ?>">资讯管理</a></li>
                    <li><a href="<?= Url::toRoute(['articles/index']) ?>">文章管理</a></li>
                </ul>
            </li>
            <li<?= in_array($controllerId, ['brands', 'categories', 'items', 'payment-configs']) ? ' class="active"' : '' ?>>
                <a href="javascript:;"><i class="fa fa-shopping-cart"></i> <span class="nav-label">店铺管理</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li<?= $controllerId == 'brands' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['brands/index']) ?>">品牌管理</a></li>
                    <li<?= $controllerId == 'categories' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['categories/index']) ?>">分类管理</a></li>
                    <li><a href="<?= Url::toRoute(['attributes/index']) ?>">属性管理</a></li>
                    <li<?= $controllerId == 'payment-configs' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['payment-configs/index']) ?>">支付管理</a></li>
                    <li><a href="<?= Url::toRoute(['posts/index']) ?>">邮费模版管理</a></li>
                    <li<?= $controllerId == 'items' ? ' class="active"' : '' ?>><a href="<?= Url::toRoute(['items/index']) ?>">商品管理</a></li>
                    <li><a href="<?= Url::toRoute(['orders/index']) ?>">订单管理</a></li>
                    <li><a href="<?= Url::toRoute(['comments/index']) ?>">评论管理</a></li>

                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-balance-scale"></i> <span class="nav-label">分销管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="graph_flot.html">等级管理</a></li>
                    <li><a href="graph_morris.html">分成管理</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wechat"></i> <span class="nav-label">微信管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="graph_flot.html">帐号设置</a></li>
                    <li><a href="graph_morris.html">素材管理</a></li>
                    <li><a href="graph_rickshaw.html">客服管理</a></li>
                    <li><a href="graph_chartjs.html">文本消息</a></li>
                    <li><a href="graph_chartist.html">图片消息</a></li>
                    <li><a href="graph_peity.html">视频消息</a></li>
                    <li><a href="graph_sparkline.html">语音消息</a></li>
                    <li><a href="graph_sparkline.html">粉丝管理</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>