<?php

namespace app\modules\api\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $icon_path
 * @property string $description
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $enabled
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Brand extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    public function fields()
    {
        return [
            'id',
            'alias',
            'name',
            'iconPath' => function () {
                return $this->icon_path;
            },
            'description',
            'ordering',
            'enabled',
        ];
    }

}
