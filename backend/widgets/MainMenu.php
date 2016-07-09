<?php

namespace backend\widgets;

use app\models\User;
use Yii;
use yii\base\Widget;

/**
 * 顶部菜单
 */
class MainMenu extends Widget
{

    public function getItems()
    {

        $controller = $this->view->context;
        $controllerId = $controller->id;
        $actionId = $controller->action->id;
        return [
            [
                'label' => '首页',
                'url' => ['default/index'],
                'active' => $controllerId == 'default',
            ],
            [
                'label' => '全局管理',
                'url' => ['global/index'],
                'active' => in_array($controllerId, ['global', 'tenants', 'users', 'labels', 'members', 'ads', 'news', 'articles']),
            ],
            [
                'label' => '店铺管理',
                'url' => ['shop/index'],
                'active' => in_array($controllerId, ['shop', 'brands', 'categories', 'types', 'specifications', 'payment-configs', 'posts', 'items', 'orders', 'comments']),
            ],
        ];
    }

    public function run()
    {
        return $this->render('MainMenu', [
                'items' => $this->getItems(),
        ]);
    }

}
