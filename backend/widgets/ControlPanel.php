<?php

namespace backend\widgets;

use common\models\Yad;
use Yii;
use yii\db\Query;

/**
 * 控制面板
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class ControlPanel extends \yii\base\Widget
{

    public function getTenants()
    {
        $tenantId = Yad::getTenantId();
        return (new Query())
                ->select('name')
                ->from('{{%tenant}}')
                ->where($tenantId ? ['<>', 'id', $tenantId] : [])
                ->indexBy('id')
                ->column();
    }

    public function getItems()
    {
        return [
            [
                'label' => '系统管理',
                'items' => [
                    [
                        'label' => '站点管理',
                        'url' => ['tenants/index'],
                    ],
                    [
                        'label' => '系统用户管理',
                        'url' => ['users/index'],
                    ],
                ]
            ],
            [
                'label' => '站点管理',
                'items' => [
                    [
                        'label' => '推送位管理',
                        'url' => ['labels/index'],
                    ],
                    [
                        'label' => '会员管理',
                        'url' => ['members/index'],
                    ],
                ]
            ],
            [
                'label' => '内容管理',
                'items' => [
                    [
                        'label' => '广告管理',
                        'url' => ['ads/index'],
                    ],
                    [
                        'label' => '资讯管理',
                        'url' => ['news/index'],
                    ],
                    [
                        'label' => '文章管理',
                        'url' => ['articles/index'],
                    ],
                ]
            ],
            [
                'label' => '店铺管理',
                'items' => [
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
                ]
            ],
        ];
    }

    public function run()
    {
        $controller = $this->view->context;

        return $this->render('ControlPanel', [
                'items' => $this->getItems(),
        ]);
    }

}
