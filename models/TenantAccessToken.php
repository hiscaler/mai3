<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tenant_access_token}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $access_token
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class TenantAccessToken extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tenant_access_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'access_token'], 'required'],
            ['enabled', 'boolean'],
            [['tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name', 'access_token'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('tenantAccessToken', 'Name'),
            'access_token' => Yii::t('tenantAccessToken', 'Access Token'),
        ]);
    }

    // 数据关联定义
    public function getTenant()
    {
        return $this->hasOne(Tenant::className(), ['id' => 'tenant_id']);
    }

}
