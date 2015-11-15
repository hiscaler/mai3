<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%payment_config}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $config
 * @property string $description
 * @property integer $ordering
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class PaymentConfig extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name', 'description'], 'required'],
            [['config', 'description'], 'string'],
            [['ordering', 'status', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['key'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'name' => Yii::t('app', 'Name'),
            'config' => Yii::t('app', 'Config'),
            'description' => Yii::t('app', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
            'status' => Yii::t('app', 'Status'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    // 事件
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->config = serialize($this->config);

            return true;
        } else {
            return false;
        }
    }

}
