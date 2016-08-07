<?php

namespace app\modules\admin\widgets;

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
        $controllerId = $this->view->context->id;
        return [
            [
                'label' => '系统管理',
                'items' => [
                    [
                        'label' => '站点管理',
                        'url' => ['tenants/index'],
                        'active' => $controllerId == 'tenants',
                    ],
                    [
                        'label' => '系统用户管理',
                        'url' => ['users/index'],
                        'active' => $controllerId == 'users',
                    ],
                ]
            ],
            [
                'label' => '站点管理',
                'items' => [
                    [
                        'label' => '推送位管理',
                        'url' => ['labels/index'],
                        'active' => $controllerId == 'labels',
                    ],
                    [
                        'label' => '会员组管理',
                        'url' => ['user-groups/index'],
                        'active' => $controllerId == 'user-groups',
                    ],
                ]
            ],
            [
                'label' => '内容管理',
                'items' => [
                    [
                        'label' => '广告管理',
                        'url' => ['ads/index'],
                        'active' => $controllerId == 'ads',
                    ],
                    [
                        'label' => '资讯管理',
                        'url' => ['news/index'],
                        'active' => $controllerId == 'news',
                    ],
                    [
                        'label' => '文章管理',
                        'url' => ['articles/index'],
                        'active' => $controllerId == 'articles',
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
