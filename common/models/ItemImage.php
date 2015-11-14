<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item_image}}".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $url
 * @property string $path
 * @property string $description
 * @property integer $ordering
 * @property integer $created_at
 * @property integer $created_by
 */
class ItemImage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'description'], 'required'],
            [['item_id', 'ordering', 'created_at', 'created_by'], 'integer'],
            [['url', 'path'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'url' => Yii::t('app', 'Url'),
            'path' => Yii::t('app', 'Path'),
            'description' => Yii::t('app', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
