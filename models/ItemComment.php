<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%item_comment}}".
 *
 * @property integer $id
 * @property integer $level
 * @property integer $item_id
 * @property string $username
 * @property string $tel
 * @property string $email
 * @property string $message
 * @property integer $return_user_id
 * @property integer $return_datetime
 * @property string $return_message
 * @property integer $enabled
 * @property integer $ip_address
 * @property integer $created_at
 * @property integer $created_by
 */
class ItemComment extends \yii\db\ActiveRecord
{

    const LEVEL_GOOD = 0; // 好评
    const LEVEL_COMMON = 1; // 中评
    const LEVEL_BAD = 2; // 差评

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'item_id', 'return_user_id', 'return_datetime', 'enabled', 'ip_address', 'created_at', 'created_by'], 'integer'],
            [['item_id', 'message', 'ip_address', 'created_at', 'created_by'], 'required'],
            [['message', 'return_message'], 'string'],
            [['username'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level' => Yii::t('itemComment', 'Level'),
            'level_text' => Yii::t('itemComment', 'Level'),
            'item_id' => Yii::t('itemComment', 'Item ID'),
            'username' => Yii::t('itemComment', 'Username'),
            'tel' => Yii::t('itemComment', 'Tel'),
            'email' => Yii::t('itemComment', 'Email'),
            'message' => Yii::t('itemComment', 'Message'),
            'return_user_id' => Yii::t('itemComment', 'Return User'),
            'return_datetime' => Yii::t('itemComment', 'Return Datetime'),
            'return_message' => Yii::t('itemComment', 'Return Message'),
            'enabled' => Yii::t('app', 'Enabled'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'created_at' => Yii::t('itemComment', 'Created At'),
            'created_by' => Yii::t('itemComment', 'Created By'),
        ];
    }
    
    /**
     * 评价等级
     * @return array
     */
    public static function levelOptions()
    {
        return [
            self::LEVEL_GOOD => '好评',
            self::LEVEL_COMMON => '中评',
            self::LEVEL_BAD => '差评',
        ];
    }
    
    public function getLevel_text() {
        $options = self::levelOptions();
        
        return isset($options[$this->level]) ? $options[$this->level] : null;
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * 提交人
     */
    public function getCreater()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$insert && !empty($this->return_message)) {
                $this->return_user_id = Yii::$app->getUser()->getId();
                $this->return_datetime = time();
            }
            return true;
        } else {
            return false;
        }
    }

}
