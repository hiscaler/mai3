<?php

namespace common\models;

use PDO;
use Yii;
use yii\db\Query;
use yii\web\HttpException;

/**
 * This is the model class for table "tenant".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $language
 * @property string $timezone
 * @property string $date_format
 * @property string $time_format
 * @property string $datetime_format
 * @property string $domain_name
 * @property integer $status
 * @property string $description
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Tenant extends BaseActiveRecord
{

    private $_modules;
    public $modules;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tenant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'language', 'timezone', 'domain_name', 'status'], 'required'],
            [['key', 'name', 'domain_name', 'description', 'date_format', 'time_format', 'datetime_format'], 'trim'],
            ['key', 'match', 'pattern' => '/^[1-9]{1}[0-9]{11}$/'],
            ['domain_name', 'match', 'pattern' => '/[a-zA-Z0-9][-a-zA-Z0-9]{1,62}(.[a-zA-Z0-9][-a-zA-Z0-9]{1,62})+.?/'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            ['status', 'boolean'],
            [['key', 'name', 'description'], 'string', 'max' => 255],
            [['domain_name'], 'string', 'max' => 100],
            [['language'], 'string', 'max' => 10],
            [['timezone', 'date_format', 'time_format', 'datetime_format', 'timezone'], 'string', 'max' => 20],
            ['key', 'unique'],
            ['modules', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'key' => Yii::t('tenant', 'Key'),
            'name' => Yii::t('tenant', 'Name'),
            'language' => Yii::t('tenant', 'Language'),
            'timezone' => Yii::t('tenant', 'Timezone'),
            'date_format' => Yii::t('tenant', 'Date Format'),
            'time_format' => Yii::t('tenant', 'Time Format'),
            'datetime_format' => Yii::t('tenant', 'Datetime Format'),
            'domain_name' => Yii::t('tenant', 'Domain Name'),
            'description' => Yii::t('tenant', 'Description'),
            'modules' => Yii::t('tenant', 'Modules'),
        ]);
    }

    /**
     * 管理用户
     * @return array
     */
    public function getUsers()
    {
        return (new Query())
                ->select(['u.id', 'u.username', 'u.nickname', 'u.email', 'u.status', 't.status', 't.role', 'tug.name AS group_name'])
                ->from('{{%tenant_user}} t')
                ->leftJoin('{{%user}} u', '[[t.user_id]] = [[u.id]]')
                ->leftJoin('{{%tenant_user_group}} tug', '[[t.user_group_id]] = [[tug.id]]')
                ->where(['t.tenant_id' => $this->id])
                ->all();
    }

    /**
     * 获取站点定义的 access token
     * @return ActiveRecord
     */
    public function getAccessTokens()
    {
        return $this->hasMany(TenantAccessToken::className(), ['tenant_id' => 'id']);
    }

    /**
     * 获取租赁站点管理用户分组
     * @param integer $tenantId
     * @return array
     */
    public static function userGroups($tenantId = null)
    {
        if ($tenantId === null) {
            $tenantId = Yad::getTenantId();
        }
        $items = (new Query())
            ->select('name')
            ->from('{{%tenant_user_group}}')
            ->where([
                'tenant_id' => $tenantId === null ? Yad::getTenantId() : $tenantId,
                'enabled' => Option::BOOLEAN_TRUE
            ])
            ->orderBy(['alias' => SORT_ASC])
            ->indexBy('id')
            ->column();

        return $items;
    }

    /**
     * 获取租赁站点管理用户
     * @return array
     */
    public static function users()
    {
        $items = (new Query())
            ->select('u.username')
            ->from('{{%tenant_user}} t')
            ->leftJoin('{{%user}} u', '[[t.user_id]] = [[u.id]]')
            ->where([
                'tenant_id' => Yad::getTenantId(),
            ])
            ->orderBy(['u.username' => SORT_ASC])
            ->indexBy('u.id')
            ->column();

        return $items;
    }

    /**
     * 租赁站点定义的审核流程规则
     * @return array
     */
    public static function workflowRules($tenantId = null)
    {
        $items = (new Query())
            ->select('name')
            ->from('{{%workflow_rule}}')
            ->where([
                'tenant_id' => $tenantId === null ? Yad::getTenantId() : $tenantId
            ])
            ->indexBy('id')
            ->column();

        return $items;
    }

    // Events

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{%tenant}}')->queryScalar();
                $this->key = date('Ymd') . sprintf('%04d', $count + 1);
            }

            return true;
        } else {
            return false;
        }
    }

}
