<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\base\Security;
use yii\web\IdentityInterface;

class BaseUser extends BaseActiveRecord implements IdentityInterface
{

    /**
     * User status
     */
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DELETED = 3;

    /**
     * User roles
     */
    const ROLE_GUEST = 0;
    const ROLE_ADMINISTRATOR = 1;
    const ROLE_USER = 10;

    private $_oldStatus;

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
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
        ]);
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getRole()
    {
        return $this->role;
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

    /**
     * Return user role options
     * @return array
     */
    public static function roleOptions()
    {
        return [
            self::ROLE_GUEST => Yii::t('user', 'Guest'),
            self::ROLE_ADMINISTRATOR => Yii::t('user', 'Administrator'),
            self::ROLE_USER => Yii::t('user', 'User'),
        ];
    }

    /**
     * Return user status options
     * @return array
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => Yii::t('user', 'Pending'),
            self::STATUS_ACTIVE => Yii::t('user', 'Active'),
            self::STATUS_DISABLED => Yii::t('user', 'Disabled'),
            self::STATUS_DELETED => Yii::t('user', 'Deleted'),
        ];
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldStatus = $this->status;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->auth_key = (new Security())->generateRandomString();
                if ($this->status === null) {
                    $this->status = self::STATUS_PENDING;
                }

                $this->register_ip = Yii::$app->getRequest()->getUserIP();
                $this->login_count = 0;
                $this->created_by = $this->updated_by = Yii::$app->user->id ? : 0;
                $this->deleted_by = $this->deleted_at = null;
            } elseif ($this->_oldStatus == self::STATUS_DELETED && $this->status != self::STATUS_DELETED) {
                $this->deleted_by = $this->deleted_at = null;
            }

            return true;
        } else {
            return false;
        }
    }

}
