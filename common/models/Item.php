<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $sn
 * @property string $name
 * @property integer $market_price
 * @property integer $member_price
 * @property integer $cost_price
 * @property string $picture_path
 * @property integer $clicks_count
 * @property integer $favorites_count
 * @property integer $sales_count
 * @property integer $stocks_count
 * @property integer $default
 * @property integer $enabled
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Item extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'sn', 'name', 'market_price', 'member_price', 'cost_price', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['product_id', 'market_price', 'member_price', 'cost_price', 'clicks_count', 'favorites_count', 'sales_count', 'stocks_count', 'default', 'enabled', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['sn'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50],
            [['picture_path'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('item', 'ID'),
            'product_id' => Yii::t('item', 'Product ID'),
            'sn' => Yii::t('item', 'Sn'),
            'name' => Yii::t('item', 'Name'),
            'market_price' => Yii::t('item', 'Market Price'),
            'member_price' => Yii::t('item', 'Member Price'),
            'cost_price' => Yii::t('item', 'Cost Price'),
            'picture_path' => Yii::t('item', 'Picture Path'),
            'clicks_count' => Yii::t('item', 'Clicks Count'),
            'favorites_count' => Yii::t('item', 'Favorites Count'),
            'sales_count' => Yii::t('item', 'Sales Count'),
            'stocks_count' => Yii::t('item', 'Stocks Count'),
            'default' => Yii::t('item', 'Default'),
            'enabled' => Yii::t('item', 'Enabled'),
            'status' => Yii::t('item', 'Status'),
        ];
    }

}
