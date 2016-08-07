<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%type_brand}}".
 *
 * @property integer $type_id
 * @property integer $brand_id
 */
class TypeBrand extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%type_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'brand_id'], 'required'],
            [['type_id', 'brand_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => Yii::t('app', 'Item Type ID'),
            'brand_id' => Yii::t('app', 'Brand ID'),
        ];
    }

}
