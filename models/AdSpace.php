<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%ad_space}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $alias
 * @property string $name
 * @property integer $width
 * @property integer $height
 * @property string $description
 * @property integer $ads_count
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class AdSpace extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_space}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'width', 'height', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['group_id'], 'default', 'value' => 0],
            ['enabled', 'boolean'],
            [['alias', 'name', 'width', 'height', 'description'], 'required'],
            [['alias', 'name', 'description'], 'trim'],
            [['alias', 'name', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'alias' => Yii::t('adSpace', 'Alias'),
            'name' => Yii::t('adSpace', 'Name'),
            'size' => Yii::t('adSpace', 'Size'),
            'width' => Yii::t('adSpace', 'Width'),
            'height' => Yii::t('adSpace', 'Height'),
            'ads_count' => Yii::t('adSpace', 'Ads Count'),
            'description' => Yii::t('adSpace', 'Description'),
            'alias' => Yii::t('adSpace', 'Alias'),
        ]);
    }

    public static function groupOptions()
    {
        return [];
//        return GroupOption::getItems('ad.space.group');
    }

    public static function spaceOptions()
    {
        $options = (new Query())
            ->select('name')
            ->from('{{%ad_space}}')
            ->where(['enabled' => Constant::BOOLEAN_TRUE])
            ->indexBy('id')
            ->column();

        return $options;
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->ads_count = 0;
            }

            return true;
        } else {
            return false;
        }
    }

}
