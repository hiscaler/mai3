<?php

namespace common\models;

use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $icon_path
 * @property string $description
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Brand extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['ordering', 'tenant_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['ordering'], 'default', 'value' => 1],
            [['slug', 'name'], 'string', 'max' => 20],
            [['icon_path'], 'string', 'max' => 100],
            [['slug'], 'unique'],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'slug' => Yii::t('app', 'Slug'),
            'name' => Yii::t('app', '名称'),
            'icon_path' => Yii::t('app', '品牌标志'),
            'description' => Yii::t('app', '描述'),
            'ordering' => Yii::t('app', '排序'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->tenant_id = 1;
                $this->created_at = $this->updated_at = time();
                $this->created_by = $this->updated_by = Yii::$app->getUser()->getId();
            }

            if (empty($this->slug) && !empty($this->name)) {
                $this->slug = Inflector::slug($this->name);
            }

            return true;
        } else {
            return false;
        }
    }

}
