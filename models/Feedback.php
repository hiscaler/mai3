<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $username
 * @property string $tel
 * @property string $email
 * @property string $title
 * @property string $message
 * @property string $ip_address
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 */
class Feedback extends \yii\db\ActiveRecord
{

    /**
     * Status values
     */
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'title', 'message'], 'required'],
            [['username', 'tel', 'email', 'title', 'message'], 'trim'],
            ['group_id', 'default', 'value' => 0],
            [['group_id', 'status', 'tenant_id', 'created_by', 'created_at'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 13],
            [['email'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 100],
            [['message'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'group_id' => Yii::t('app', 'Group'),
            'username' => Yii::t('feedback', 'Username'),
            'tel' => Yii::t('feedback', 'Tel'),
            'email' => Yii::t('feedback', 'Email'),
            'title' => Yii::t('feedback', 'Title'),
            'message' => Yii::t('feedback', 'Message'),
            'ip_address' => Yii::t('app', 'Ip Address'),
        ]);
    }

    public static function statusOptions()
    {
        return [
            static::STATUS_PENDING => Yii::t('app', 'Pending'),
            static::STATUS_ACTIVE => Yii::t('app', 'Active'),
            static::STATUS_DELETE => Yii::t('app', 'Delete'),
        ];
    }

    public static function groupOptions()
    {
        return Lookup::getValue('system.models.feedback.group', []);
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->status = static::STATUS_PENDING;
                $this->ip_address = ip2long(Yii::$app->getRequest()->getUserIP());
            }

            return true;
        } else {
            return false;
        }
    }

}
