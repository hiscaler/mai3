<?php

namespace app\modules\api\models;

use app\modules\api\helpers\Util;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $alias
 * @property string $name
 * @property integer $parent_id
 * @property integer $level
 * @property string $parent_ids
 * @property string $parent_names
 * @property string $icon_path
 * @property string $description
 * @property integer $enabled
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Category extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    public function fields()
    {
        return [
            'id',
            'type',
            'alias',
            'name',
            'parentId' => 'parent_id',
            'level',
            'icon' => function () {
                return Util::fixStaticAssetUrl($this->icon_path);
            },
            'description',
            'enabled',
            'ordering',
            'createdAt' => 'created_at',
            'createdAtPretty' => function () {
                return Yii::$app->getFormatter()->asDatetime($this->created_at);
            },
            'createdBy' => 'created_by',
            'updatedAt' => 'updated_at',
            'updatedBy' => 'updated_by',
        ];
    }

}
