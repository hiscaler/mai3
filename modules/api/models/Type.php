<?php

namespace app\modules\api\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%type}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ordering
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Type extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%type}}';
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'ordering',
            'enabled',
            'createdAt' => 'created_at',
            'createdBy' => 'created_by',
            'updatedAt' => 'updated_at',
            'updatedBy' => 'updated_by',
        ];
    }

}
