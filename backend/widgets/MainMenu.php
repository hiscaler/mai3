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
                'url' => ['site/index'],
                'active' => $controllerId == 'site',
            ],
            [
                'label' => '店铺管理',
                'url' => ['orders/index'],
                'active' => $controllerId == 'orders',
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
