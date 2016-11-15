<?php

namespace app\modules\admin\widgets;

use Yii;

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
                'label' => Yii::t('app', 'Brands'),
                'url' => ['brands/index'],
                'active' => $controllerId == 'brands',
            ],
            [
                'label' => Yii::t('app', 'Types'),
                'url' => ['types/index'],
                'active' => $controllerId == 'types',
            ],
            [
                'label' => Yii::t('app', 'Specifications'),
                'url' => ['specifications/index'],
                'active' => $controllerId == 'specifications',
            ],
//            [
//                'label' => Yii::t('app', 'Payment Configs'),
//                'url' => ['payment-configs/index'],
//                'active' => $controllerId == 'payment-configs',
//            ],
            [
                'label' => Yii::t('app', 'Products'),
                'url' => ['products/index'],
                'active' => in_array($controllerId, ['products', 'items']),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Products'),
                        'url' => ['products/index'],
                        'active' => $controllerId == 'products',
                    ],
                    [
                        'label' => Yii::t('app', 'Items'),
                        'url' => ['items/index'],
                        'active' => $controllerId == 'items',
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Members'),
                'url' => ['members/index'],
                'active' => $controllerId == 'members',
            ],
            [
                'label' => Yii::t('app', 'Orders'),
                'url' => ['orders/index'],
                'active' => $controllerId == 'orders',
            ],
            [
                'label' => Yii::t('app', 'Item Comments'),
                'url' => ['item-comments/index'],
                'active' => $controllerId == 'item-comments',
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
