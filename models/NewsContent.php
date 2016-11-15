<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%news_content}}".
 *
 * @property integer $id
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
