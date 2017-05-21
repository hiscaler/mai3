<?php

namespace app\modules\api\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $brand_id
 * @property string $sn
 * @property string $name
 * @property string $market_price
 * @property string $shop_price
 * @property string $member_price
 * @property string $picture_path
 * @property string $keywords
 * @property string $description
 * @property integer $online
 * @property integer $on_off_datetime
 * @property integer $view_require_credits
 * @property integer $ordering
 * @property integer $clicks_count
 * @property integer $sales_count
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Product extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    public function fields()
    {
        return [
            'id',
            'categoryId' => 'category_id',
            'typeId' => 'type_id',
            'brandId' => 'brand_id',
            'sn',
            'name',
            'marketPrice' => 'market_price',
            'shopPrice' => 'shop_price',
            'memberPrice' => 'member_price',
            'picturePath' => 'picture_path',
            'keywords',
            'description',
            'online',
            'onOffDatetime' => 'on_off_datetime',
            'viewRequireCredits' => 'view_require_credits',
            'ordering',
            'clicksCount' => 'clicks_count',
            'salesCount' => 'sales_count',
            'status',
            'createdAt' => 'created_at',
            'createdBy' => 'created_by',
            'updatedAt' => 'updated_at',
            'updatedBy' => 'updated_by',
        ];
    }

    public function extraFields()
    {
        return ['brand', 'images'];
    }

    /**
     * 所属分类
     * @return ActiveRecord
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * 所属类型
     * @return ActiveRecord
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
    }

    /**
     * 所属品牌
     * @return ActiveRecord
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * 商品图片
     * @return ActiveRecord
     */
    public function getImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * 商品单品
     * @return array
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['product_id' => 'id']);
    }

}
