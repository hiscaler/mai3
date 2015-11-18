<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%specification_value}}".
 *
 * @property integer $id
 * @property integer $specification_id
 * @property string $text
 * @property string $icon_path
 * @property integer $ordering
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class SpecificationValue extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%specification_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['specification_id', 'text', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['specification_id', 'ordering', 'status', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['text'], 'string', 'max' => 30],
            [['icon_path'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'specification_id' => Yii::t('app', 'Specification ID'),
            'text' => Yii::t('app', 'Text'),
            'icon_path' => Yii::t('app', 'Icon Path'),
            'ordering' => Yii::t('app', 'Ordering'),
            'status' => Yii::t('app', 'Status'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
