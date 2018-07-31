<?php

namespace app\models;

use PDO;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tenant_user}}".
 *
 * @property integer $id
 * @property integer $tenant_id
 * @property integer $user_id
 * @property integer $role
 * @property integer $rule_id
 * @property integer $enabled
 * @property integer $user_group_id
 */
class TenantUser extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tenant_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tenant_id', 'user_id', 'role'], 'required'],
            [['rule_id', 'user_group_id'], 'default', 'value' => 0],
            [['tenant_id', 'user_id', 'role', 'rule_id', 'enabled', 'user_group_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tenant_id' => Yii::t('tenantUser', 'Tenant ID'),
            'user_id' => Yii::t('tenantUser', 'User ID'),
            'username' => Yii::t('user', 'Username'),
            'role' => Yii::t('user', 'Role'),
            'rule_id' => Yii::t('tenantUser', 'Rule'),
            'enabled' => Yii::t('app', 'Enabled'),
            'user_group_id' => Yii::t('tenantUser', 'User Group'),
        ];
    }

    /**
     * @return false|null|string
     * @throws \yii\db\Exception
     */
    public function getUsername()
    {
        return Yii::$app->getDb()->createCommand('SELECT [[username]] FROM {{%user}} WHERE [[id]] = :id')->bindValue(':id', $this->user_id, PDO::PARAM_INT)->queryScalar();
    }

}
