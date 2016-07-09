<?php

namespace backend\widgets;

use common\models\Yad;
use Yii;
use yii\db\Query;

/**
 * 店铺管理控制面板
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class ShopControlPanel extends \yii\base\Widget
{

    public function getItems()
    {
        return [

            [
                'label' => '品牌管理',
                'url' => ['brands/index'],
            ],
            [
                'label' => '分类管理',
                'url' => ['categories/index'],
            ],
            [
                'label' => '商品类型管理',
                'url' => ['types/index'],
            ],
            [
                'label' => '商品规格管理',
                'url' => ['specifications/index'],
            ],
            [
                'label' => '支付管理',
                'url' => ['payment-configs/index'],
            ],
            [
                'label' => '邮费模版管理',
                'url' => ['posts/index'],
            ],
            [
                'label' => '商品管理',
                'url' => ['items/index'],
            ],
            [
                'label' => '订单管理',
                'url' => ['orders/index'],
            ],
            [
                'label' => '评论管理',
                'url' => ['comments/index'],
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
