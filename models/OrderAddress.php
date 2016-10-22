<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_address}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $linkman
 * @property string $address
 * @property string $tel
 * @property integer $created_at
 * @property integer $created_by
 */
class OrderAddress extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'linkman', 'address', 'tel', 'created_at', 'created_by'], 'required'],
            [['order_id', 'created_at', 'created_by'], 'integer'],
            [['linkman'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 60],
            [['tel'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('orderAddress', 'Order ID'),
            'linkman' => Yii::t('orderAddress', 'Linkman'),
            'address' => Yii::t('orderAddress', 'Address'),
            'tel' => Yii::t('orderAddress', 'Tel'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
