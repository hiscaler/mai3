<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $sn
 * @property integer $quantity
 * @property string $total_amount
 * @property string $discount_amount
 * @property string $express_fee
 * @property string $actual_amount
 * @property integer $address_id
 * @property string $express_id
 * @property string $express_sn
 * @property integer $status
 * @property string $remark
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Order extends \yii\db\ActiveRecord
{

    /**
     * 订单状态
     */
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_VOID = 3;
    const STATUS_DELETED = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sn', 'quantity'], 'required'],
            [['quantity', 'address_id', 'status', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['total_amount', 'discount_amount', 'express_fee', 'actual_amount'], 'number'],
            [['sn'], 'string', 'max' => 32],
            [['express_id'], 'string', 'max' => 12],
            [['express_sn'], 'string', 'max' => 15],
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
            'sn' => Yii::t('order', 'Sn'),
            'quantity' => Yii::t('order', 'Quantity'),
            'total_amount' => Yii::t('order', 'Total Amount'),
            'discount_amount' => Yii::t('order', 'Discount Amount'),
            'express_fee' => Yii::t('order', 'Express Fee'),
            'actual_amount' => Yii::t('order', 'Actual Amount'),
            'address_id' => Yii::t('order', 'Address'),
            'express_id' => Yii::t('order', 'Express'),
            'express_sn' => Yii::t('order', 'Express Sn'),
            'status' => Yii::t('order', 'Status'),
            'status_text' => Yii::t('order', 'Status'),
            'remark' => Yii::t('order', 'Remark'),
            'tenant_id' => Yii::t('order', 'Tenant ID'),
            'created_at' => Yii::t('order', 'Created At'),
            'created_by' => Yii::t('order', 'Created By'),
            'creater.username' => Yii::t('order', 'Created By'),
            'updated_at' => Yii::t('order', 'Updated At'),
            'updated_by' => Yii::t('order', 'Updated By'),
        ];
    }

    /**
     * 生成订单号
     * @return string
     */
    public static function generateSn()
    {
        $today = \yadjet\helpers\DatetimeHelper::getTodayRange();
        $count = Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%order}} WHERE [[created_at]] BETWEEN :begin AND :end', [':begin' => $today[0], ':end' => $today[1]])->queryScalar();
        return date('YmdHis') . sprintf('%04d', $count + 1);
    }

    /**
     * 订单详情
     */
    public function getDetails()
    {
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }

    /**
     * 收货地址
     */
    public function getAddress()
    {
        return $this->hasOne(OrderAddress::className(), ['id' => 'address_id']);
    }

    /**
     * 下单人
     */
    public function getCreater()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * 订单状态选项
     * @return array
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => '待审核',
            self::STATUS_ACTIVE => '有效',
            self::STATUS_CANCEL => '取消',
            self::STATUS_VOID => '无效',
            self::STATUS_DELETED => '删除',
        ];
    }

    /**
     * 订单状态
     * @return string|mixed
     */
    public function getStatus_text()
    {
        $options = self::statusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : null;
    }

}
