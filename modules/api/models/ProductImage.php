<?php

namespace app\modules\api\models;

/**
 * This is the model class for table "{{%product_image}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $url
 * @property string $path
 * @property string $description
 * @property integer $ordering
 * @property integer $created_at
 * @property integer $created_by
 */
class ProductImage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_image}}';
    }

    public function fields()
    {
        return [
            'id',
            'url',
            'path',
            'description',
        ];
    }

    /**
     * 商品图片
     *
     * @return \yii\db\ActiveRecord
     */
    public function getImages()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id']);
    }

}
