<?php

namespace app\forms;

/**
 * 会员注册表单
 */
class SignupForm extends \app\models\User
{

    public $password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['password', 'confirm_password', 'email'], 'required'],
            [['password', 'confirm_password'], 'string', 'min' => 6, 'max' => 12],
            ['confirm_password', 'compare', 'compareAttribute' => 'password',
                'message' => '两次输入的密码不一致，请重新输入。'
            ],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'confirm_password' => '确认密码',
            'email' => '邮箱',
        ];
    }

    // Events
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->type = self::TYPE_MEMBER;

            return true;
        } else {
            return false;
        }
    }

}
