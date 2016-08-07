<?php

namespace app\modules\admin\widgets;

use app\models\Yad;
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
        ];
    }

    public function run()
    {
        return $this->render('ControlPanel', [
                'items' => $this->getItems(),
        ]);
    }

}
