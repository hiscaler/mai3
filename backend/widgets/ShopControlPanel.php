<?php

namespace backend\widgets;

/**
 * 店铺管理控制面板
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class ShopControlPanel extends \yii\base\Widget
{

    public function getItems()
    {
        $controllerId = $this->view->context->id;
        return [
            [
                'label' => '品牌管理',
                'url' => ['brands/index'],
                'active' => $controllerId == 'brands',
            ],
            [
                'label' => '分类管理',
                'url' => ['categories/index'],
                'active' => $controllerId == 'categories',
            ],
            [
                'label' => '商品类型管理',
                'url' => ['types/index'],
                'active' => $controllerId == 'types',
            ],
            [
                'label' => '商品规格管理',
                'url' => ['specifications/index'],
                'active' => $controllerId == 'specifications',
            ],
            [
                'label' => '支付管理',
                'url' => ['payment-configs/index'],
                'active' => $controllerId == 'payment-configs',
            ],
            [
                'label' => '邮费模版管理',
                'url' => ['posts/index'],
                'active' => $controllerId == 'posts',
            ],
            [
                'label' => '商品管理',
                'url' => ['items/index'],
                'active' => $controllerId == 'items',
            ],
            [
                'label' => '订单管理',
                'url' => ['orders/index'],
                'active' => $controllerId == 'orders',
            ],
            [
                'label' => '评论管理',
                'url' => ['comments/index'],
                'active' => $controllerId == 'comments',
            ],
        ];
    }

    public function run()
    {
        return $this->render('ControlPanel', [
                'items' => $this->getItems(),
        ]);
    }

}
