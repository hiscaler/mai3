<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\base\Widget;

/**
 * 租赁详情选项卡
 */
class TenantTabs extends Widget
{

    public $model;

    public function getItems()
    {
        $tenantId = Yii::$app->getRequest()->get('id');
        $items = [
            [
                'label' => Yii::t('tenant', 'Base Informations'),
                'url' => ['tenants/view', 'id' => $tenantId],
                'id' => 'panel-tenant-detail',
            ],
            [
                'label' => Yii::t('tenant', 'Manage Modules'),
                'url' => ['tenants/view', 'id' => $tenantId],
                'id' => 'panel-tenant-modules',
            ],
            [
                'label' => Yii::t('tenant', 'Manage Users'),
                'url' => ['tenants/view', 'id' => $tenantId],
                'id' => 'panel-tenant-users',
            ],
            [
                'label' => Yii::t('tenant', 'Manage Access Tokens'),
                'url' => ['tenants/view', 'id' => $tenantId],
                'id' => 'panel-tenant-access-tokens',
            ],
        ];

        return $items;
    }

    public function run()
    {
        return $this->render('TenantTabs', [
                'model' => $this->model,
                'items' => $this->getItems(),
        ]);
    }

}
