<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $consignee
 * @property string $address
 * @property string $mobile_phone
 * @property string $tel
 * @property string $email
 * @property integer $default
 * @property integer $member_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Address extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'consignee', 'address'], 'required'],
            [['member_id', 'created_at', 'updated_at'], 'integer'],
            ['default', 'default', 'value' => Constant::BOOLEAN_FALSE],
            [['alias', 'consignee'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 60],
            [['mobile_phone'], 'string', 'max' => 11],
            [['tel'], 'string', 'max' => 13],
            [['email'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', '地址别名'),
            'consignee' => Yii::t('app', '收货人'),
            'address' => Yii::t('app', '地址'),
            'mobile_phone' => Yii::t('app', '手机号码'),
            'tel' => Yii::t('app', '固定电话'),
            'email' => Yii::t('app', '电子邮箱'),
            'default' => Yii::t('app', 'Default'),
            'member_id' => Yii::t('app', '所属会员'),
            'created_at' => Yii::t('app', '添加时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $memberId = \Yii::$app->getUser()->getId();
                $this->member_id = $memberId;
                $this->created_at = time();
                $hasAddress = \Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%address}} WHERE [[member_id]] = :memberId', [':memberId' => $memberId])->queryScalar();
                if (!$hasAddress) {
                    $this->default = Constant::BOOLEAN_TRUE;
                }
            }
            $this->updated_at = time();

            return true;
        } else {
            return false;
        }
    }

}
