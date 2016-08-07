<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_group}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $icon_path
 * @property integer $min_credits
 * @property integer $max_credits
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class UserGroup extends \yii\db\ActiveRecord
{

    /**
     * 分组类型
     */
    const TYPE_USER_GROUP = 0;
    const TYPE_SYSTEM_GROUP = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type', 'min_credits', 'max_credits', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['min_credits', 'max_credits'], 'default', 'value' => 0],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 30],
            [['icon_path'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '分组类型',
            'name' => '组头衔',
            'icon_path' => '组图标',
            'min_credits' => '最小积分',
            'max_credits' => '最小积分',
            'created_at' => '添加时间',
            'created_by' => '添加人',
            'updated_at' => '更新时间',
            'updated_by' => '更新人',
        ];
    }

    public static function typeOptions()
    {
        return [
            self::TYPE_USER_GROUP => '用户组',
            self::TYPE_SYSTEM_GROUP => '系统组',
        ];
    }

    public function getType_text()
    {
        $options = self::typeOptions();

        return isset($options[$this->type]) ? $options[$this->type] : null;
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = $this->updated_at = time();
                $this->created_by = $this->updated_by = Yii::$app->getUser()->getId();
            } else {
                $this->updated_at = time();
                $this->updated_by = Yii::$app->getUser()->getId();
            }

            return true;
        } else {
            return false;
        }
    }

}
