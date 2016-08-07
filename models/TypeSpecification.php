<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%type_specification}}".
 *
 * @property integer $type_id
 * @property integer $specification_id
 * @property integer $ordering
 */
class TypeSpecification extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%type_specification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'specification_id'], 'required'],
            [['type_id', 'specification_id', 'ordering'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => Yii::t('app', 'Item Type ID'),
            'specification_id' => Yii::t('app', 'Specification ID'),
            'ordering' => Yii::t('app', 'Ordering'),
        ];
    }

}
