<?php

namespace backend\widgets;

use yii\base\Widget;

/**
 * 全局管理控制面板
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class GlobalControlPanel extends Widget
{

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
