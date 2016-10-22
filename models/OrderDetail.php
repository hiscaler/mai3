<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_detail}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $item_id
 * @property integer $quantity
 * @property string $price
 * @property string $discount_amount
 * @property string $express_fee
 * @property string $subtotal_amount
 * @property string $remark
 * @property integer $status
 * @property integer $updated_at
 * @property integer $updated_by
 */
class OrderDetail extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'item_id', 'quantity', 'price', 'subtotal_amount', 'updated_at', 'updated_by'], 'required'],
            [['order_id', 'item_id', 'quantity', 'status', 'updated_at', 'updated_by'], 'integer'],
            [['price', 'discount_amount', 'express_fee', 'subtotal_amount'], 'number'],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('order', 'Order ID'),
            'item_id' => Yii::t('order', 'Item ID'),
            'quantity' => Yii::t('order', 'Quantity'),
            'price' => Yii::t('order', 'Price'),
            'discount_amount' => Yii::t('order', 'Discount Amount'),
            'express_fee' => Yii::t('order', 'Express Fee'),
            'subtotal_amount' => Yii::t('order', 'Subtotal Amount'),
            'remark' => Yii::t('app', 'Remark'),
            'status' => Yii::t('app', 'Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    public function getOrder()
    {
        $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

}
