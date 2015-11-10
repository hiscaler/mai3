<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

class Option
{

    /**
     * Boolean values
     */
    const BOOLEAN_FALSE = 0;
    const BOOLEAN_TRUE = 1;

    /**
     * Status values
     */
    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_DELETED = 3;
    const STATUS_ARCHIVED = 4;

    /**
     * Boolean options
     * @return array
     */
    public static function booleanOptions()
    {
        return [
            self::BOOLEAN_FALSE => Yii::t('app', 'No'),
            self::BOOLEAN_TRUE => Yii::t('app', 'Yes'),
        ];
    }

    /**
     * 排序下拉列表框数据
     * @param integer $start
     * @param integer $max
     * @return array
     */
    public static function orderingOptions($start = 0, $max = 60)
    {
        $options = [];
        for ($i = $start; $i <= $max; $i++) {
            $options[$i] = $i;
        }
        return $options;
    }

    /**
     * Data status values
     * @return array
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_DRAFT => Yii::t('app', 'Draft'),
            self::STATUS_PENDING => Yii::t('app', 'Pending'),
            self::STATUS_PUBLISHED => Yii::t('app', 'Published'),
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
            self::STATUS_ARCHIVED => Yii::t('app', 'Archived')
        ];
    }

    public static function modelNameOptions()
    {
        $options = [];
        $tenantModules = Tenant::modules();
        $contentModels = ArrayHelper::getValue(Yii::$app->params, 'contentModules', []);
        foreach ($contentModels as $modelName => $item) {
            if (in_array($modelName, $tenantModules)) {
                $options[$modelName] = Yii::t('app', $item['label']);
            }
        }
        return $options;
    }

    /**
     * 模块列表
     * @param boolean $all // 是否返回所有模块
     * @return array
     */
    public static function modulesOptions($all = false)
    {
        $options = [];
        if ($all === true) {
            $tenantModules = Tenant::modules();
        }
        $contentModels = ArrayHelper::getValue(Yii::$app->params, 'contentModules', []);
        foreach ($contentModels as $modelName => $item) {
            if ($all === false && in_array($modelName, $tenantModules)) {
                continue;
            }
            $options[$modelName] = Yii::t('app', $item['label']);
        }

        return $options;
    }

}
