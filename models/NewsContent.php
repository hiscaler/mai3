<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%news_content}}".
 *
 * @property integer $news_id
 * @property string $content
 */
class NewsContent extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_content}}';
    }

    public function getPrimaryKey($asArray = false)
    {
        static::primaryKey();
    }

    public static function primaryKey()
    {
        return ['news_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['news_id'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'news_id' => Yii::t('news', 'News ID'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

}
