<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "mai_user".
 *
 * @property integer $id
 * @property integer $type
 * @property string $username
 * @property string $nickname
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $credits_count
 * @property integer $user_group
 * @property integer $system_group
 * @property integer $register_ip
 * @property integer $login_count
 * @property integer $last_login_ip
 * @property integer $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * 用户类型
     */
    const TYPE_USER = 0;
    const TYPE_MEMBER = 1;

    /**
     * 用户状态
     */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'role', 'credits_count', 'user_group', 'system_group', 'register_ip', 'login_count', 'last_login_ip', 'last_login_time', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['username', 'email'], 'required'],
            [['username', 'nickname'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['credits_count', 'user_group', 'system_group'], 'default', 'value' => 0],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Username'),
            'nickname' => Yii::t('user', 'Nickname'),
            'email' => Yii::t('user', 'Email'),
            'enabled' => Yii::t('app', 'Enabled'),
            'user_group' => Yii::t('user', 'User Group'),
            'user_group_text' => Yii::t('user', 'User Group'),
            'system_group' => Yii::t('user', 'System Group'),
            'system_group_text' => Yii::t('user', 'System Group'),
        ];
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_DELETED => '禁止',
            self::STATUS_ACTIVE => '激活',
        ];
    }

    public function getUser_group_text()
    {
        $options = UserGroup::userGroupOptions();

        return isset($options[$this->user_group]) ? $options[$this->user_group] : null;
    }

    public function getSystem_group_text()
    {
        $options = UserGroup::systemGroupOptions();

        return isset($options[$this->system_group]) ? $options[$this->system_group] : null;
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->nickname)) {
                $this->nickname = $this->username;
            }
            if ($insert) {
                $this->generateAuthKey();
                $this->register_ip = Yii::$app->getRequest()->getUserIP();
                if ($this->type == self::TYPE_MEMBER) {
                    $this->created_by = $this->updated_by = 0;
                } else {
                    $this->created_by = $this->updated_by = Yii::$app->getUser()->getId();
                }
                $this->created_at = $this->updated_at = time();
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
