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

    public function run()
    {
        $controller = $this->view->context;

        return $this->render('ControlPanel', [
                'identity' => Yii::$app->getUser()->getIdentity(),
                'controllerId' => $controller->id,
                'actionId' => $controller->action->id,
                'tenants' => $this->getTenants(),
        ]);
    }

}
