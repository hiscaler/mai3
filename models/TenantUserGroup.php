<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tenant_user_group}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class TenantUserGroup extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tenant_user_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['alias', 'name'], 'required'],
            ['alias', 'match', 'pattern' => '/^[a-z]+$/'],
            ['alias', 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
            [['enabled', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['alias'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 30]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('tenantUserGroup', 'Name'),
        ]);
    }

}
