<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $category_id
 * @property integer $brand_id
 * @property string $sn
 * @property string $name
 * @property string $market_price
 * @property string $shop_price
 * @property string $member_price
 * @property string $cost_price
 * @property string $picture_path
 * @property integer $clicks_count
 * @property integer $favorites_count
 * @property integer $sales_count
 * @property integer $stocks_count
 * @property integer $default
 * @property integer $enabled
 * @property integer $status
 * @property integer $online
 * @property integer $on_off_datetime
 * @property integer $view_require_credits
 * @property integer $tenant_id
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
            [['product_id', 'category_id', 'brand_id', 'sn', 'name', 'market_price', 'shop_price', 'member_price', 'cost_price', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['product_id', 'category_id', 'brand_id', 'clicks_count', 'favorites_count', 'sales_count', 'stocks_count', 'default', 'enabled', 'status', 'on_off_datetime', 'view_require_credits', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['market_price', 'shop_price', 'member_price', 'cost_price'], 'number'],
            [['online'], 'boolean'],
            [['market_price', 'shop_price', 'member_price', 'cost_price', 'view_require_credits'], 'default', 'value' => 0],
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
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('item', 'Product ID'),
            'category_id' => Yii::t('category', 'Name'),
            'brand_id' => Yii::t('brand', 'Name'),
            'sn' => Yii::t('item', 'Sn'),
            'name' => Yii::t('item', 'Name'),
            'market_price' => Yii::t('item', 'Market Price'),
            'shop_price' => Yii::t('item', 'Shop Price'),
            'member_price' => Yii::t('item', 'Member Price'),
            'cost_price' => Yii::t('item', 'Cost Price'),
            'picture_path' => Yii::t('item', 'Picture'),
            'clicks_count' => Yii::t('item', 'Clicks Count'),
            'favorites_count' => Yii::t('item', 'Favorites Count'),
            'sales_count' => Yii::t('item', 'Sales Count'),
            'stocks_count' => Yii::t('item', 'Stocks Count'),
            'default' => Yii::t('app', 'Default'),
            'enabled' => Yii::t('app', 'Enabled'),
            'status' => Yii::t('item', 'Status'),
            'online' => Yii::t('product', 'Online'),
            'on_off_datetime' => Yii::t('product', 'On Off Datetime'),
            'view_require_credits' => Yii::t('product', 'View Require Credits'),
        ];
    }

    /**
     * 所属商品
     *
     * @return \yii\db\ActiveRecord
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * 评论
     *
     * @return ActiveRecord
     */
    public function getComments()
    {
        return $this->hasMany(ItemComment::className(), ['item_id' => 'id']);
    }

    /**
     * 销售情况
     *
     * @return ActiveRecord
     */
    public function getSales()
    {
        return $this->hasMany(OrderDetail::className(), ['item_id' => 'id']);
    }

    // Events
    public function afterDelete()
    {
        parent::afterDelete();
        $cmd = Yii::$app->getDb()->createCommand();
        $cmd->delete('{{%item_specification_value}}', ['item_id' => $this->id])->execute();
        $cmd->delete('{{%item_comment}}', ['item_id' => $this->id])->execute();
    }

}
