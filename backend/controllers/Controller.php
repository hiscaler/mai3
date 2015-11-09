<?php

namespace backend\controllers;

use common\models\User;
use common\models\Yad;
use Yii;

/**
 * Controller base class
 */
class Controller extends \yii\web\Controller
{

    public function beforeActionXX($action)
    {
        if (parent::beforeAction($action)) {
            // 如果不是管理员并且没有选择管理站点的一律跳到站点选择界面
            if (Yad::getUserRole() !== User::ROLE_ADMINISTRATOR && !Yad::getTenantId() && ($this->id != 'default' && !in_array($action->id, ['choice-tenant', 'change-tenant', 'login', 'logout', 'error']))) {
                $this->redirect(['/default/choice-tenant']);
            }

            $formatter = Yii::$app->getFormatter();
            $language = Yad::getLanguage();
            if ($language) {
                Yii::$app->language = $language;
            }
            $timezone = Yad::getTimezone();
            if ($timezone) {
                Yii::$app->timeZone = $timezone;
            }

            $formatter->defaultTimeZone = Yii::$app->timeZone;
            $dateFormat = Yad::getTenantValue('dateFormat', 'php:Y-m-d');
            if ($dateFormat) {
                $formatter->dateFormat = $dateFormat;
            }
            $timeFormat = Yad::getTenantValue('timeFormat', 'php:H:i:s');
            if ($timeFormat) {
                $formatter->timeFormat = $timeFormat;
            }
            $datetimeFormat = Yad::getTenantValue('datetimeFormat', 'php:Y-m-d H:i:s');
            if ($datetimeFormat) {
                $formatter->datetimeFormat = $datetimeFormat;
            }

            return true;
        }

        return false;
    }

}
