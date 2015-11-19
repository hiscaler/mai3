<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item_type_brand}}".
 *
 * @property integer $item_type_id
 * @property integer $brand_id
 */
class ItemTypeBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_type_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_type_id', 'brand_id'], 'required'],
            [['item_type_id', 'brand_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_type_id' => Yii::t('app', 'Item Type ID'),
            'brand_id' => Yii::t('app', 'Brand ID'),
        ];
    }
}
