<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item_type_specification}}".
 *
 * @property integer $item_type_id
 * @property integer $specification_id
 * @property integer $ordering
 */
class ItemTypeSpecification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_type_specification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_type_id', 'specification_id'], 'required'],
            [['item_type_id', 'specification_id', 'ordering'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_type_id' => Yii::t('app', 'Item Type ID'),
            'specification_id' => Yii::t('app', 'Specification ID'),
            'ordering' => Yii::t('app', 'Ordering'),
        ];
    }
}
