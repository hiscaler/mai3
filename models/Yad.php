<?php

namespace app\models;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\Cookie;

class Yad
{

    public static function getLanguages()
    {
        return [
            'ar' => Yii::t('language', 'ar'),
            'bg' => Yii::t('language', 'bg'),
            'ca' => Yii::t('language', 'ca'),
            'da' => Yii::t('language', 'da'),
            'de' => Yii::t('language', 'de'),
            'en-US' => Yii::t('language', 'en-US'),
            'el' => Yii::t('language', 'el'),
            'es' => Yii::t('language', 'es'),
            'et' => Yii::t('language', 'et'),
            'fa-IR' => Yii::t('language', 'fa-IR'),
            'fi' => Yii::t('language', 'fi'),
            'fr' => Yii::t('language', 'fr'),
            'hu' => Yii::t('language', 'hu'),
            'id' => Yii::t('language', 'id'),
            'it' => Yii::t('language', 'it'),
            'ja' => Yii::t('language', 'ja'),
            'kk' => Yii::t('language', 'kk'),
            'ko' => Yii::t('language', 'ko'),
            'lt' => Yii::t('language', 'lt'),
            'lv' => Yii::t('language', 'lv'),
            'nl' => Yii::t('language', 'nl'),
            'pl' => Yii::t('language', 'pl'),
            'pt-BR' => Yii::t('language', 'pt-BR'),
            'pt-PT' => Yii::t('language', 'pt-PT'),
            'ro' => Yii::t('language', 'ro'),
            'ru' => Yii::t('language', 'ru'),
            'sk' => Yii::t('language', 'sk'),
            'sr' => Yii::t('language', 'sr'),
            'sr-Latn' => Yii::t('language', 'sr-Latn'),
            'th' => Yii::t('language', 'th'),
            'uk' => Yii::t('language', 'uk'),
            'vi' => Yii::t('language', 'vi'),
            'zh-CN' => Yii::t('language', 'zh-CN'),
            'zh-TW' => Yii::t('language', 'zh-TW')
        ];
    }

    /**
     * 时区列表
     *
     * @return array
     */
    public static function getTimezones()
    {
        return [
            'Etc/GMT' => Yii::t('timezone', 'Etc/GMT'),
            'Etc/GMT+0' => Yii::t('timezone', 'Etc/GMT+0'),
            'Etc/GMT+1' => Yii::t('timezone', 'Etc/GMT+1'),
            'Etc/GMT+10' => Yii::t('timezone', 'Etc/GMT+10'),
            'Etc/GMT+11' => Yii::t('timezone', 'Etc/GMT+11'),
            'Etc/GMT+12' => Yii::t('timezone', 'Etc/GMT+12'),
            'Etc/GMT+2' => Yii::t('timezone', 'Etc/GMT+2'),
            'Etc/GMT+3' => Yii::t('timezone', 'Etc/GMT+3'),
            'Etc/GMT+4' => Yii::t('timezone', 'Etc/GMT+4'),
            'Etc/GMT+5' => Yii::t('timezone', 'Etc/GMT+5'),
            'Etc/GMT+6' => Yii::t('timezone', 'Etc/GMT+6'),
            'Etc/GMT+7' => Yii::t('timezone', 'Etc/GMT+7'),
            'Etc/GMT+8' => Yii::t('timezone', 'Etc/GMT+8'),
            'Etc/GMT+9' => Yii::t('timezone', 'Etc/GMT+9'),
            'Etc/UTC' => Yii::t('timezone', 'Etc/UTC'),
            'PRC' => Yii::t('timezone', 'PRC'),
        ];
    }

    /**
     * 设置租赁站点信息
     *
     * @param integer $tenantId
     * @return boolean
     * @throws \yii\db\Exception
     */
    public static function setTenantData($tenantId)
    {
        $db = Yii::$app->getDb();
        $tenantUser = $db->createCommand('SELECT [[t.role]], [[t.rule_id]], [[u.role]] AS [[user_role]] FROM {{%tenant_user}} t LEFT JOIN {{%user}} u ON [[t.user_id]] = [[u.id]] WHERE [[t.tenant_id]] = :tenantId AND [[t.user_id]] = :userId AND [[t.enabled]] = :enabled')->bindValues([
            ':tenantId' => (int) $tenantId,
            ':userId' => Yii::$app->getUser()->getId(),
            ':enabled' => Constant::BOOLEAN_TRUE
        ])->queryOne();
        if ($tenantUser !== false) {
            $tenant = $db->createCommand('SELECT [[id]], [[name]], [[language]], [[timezone]], [[date_format]], [[time_format]], [[datetime_format]], [[domain_name]] FROM {{%tenant}} WHERE [[id]] = :id AND [[enabled]] = :enabled')->bindValues([
                ':id' => (int) $tenantId,
                ':enabled' => Constant::BOOLEAN_TRUE
            ])->queryOne();
            if ($tenant) {
                $cookie = new Cookie(['name' => '_tenant', 'httpOnly' => true]);
                $cookie->value = [
                    'id' => $tenant['id'],
                    'name' => $tenant['name'],
                    'language' => $tenant['language'],
                    'timezone' => $tenant['timezone'],
                    'dateFormat' => $tenant['date_format'],
                    'timeFormat' => $tenant['time_format'],
                    'datetimeFormat' => $tenant['datetime_format'],
                    'domainName' => $tenant['domain_name'],
                    'role' => $tenantUser['role'], // 站点管理用户角色
                    'rule' => $tenantUser['rule_id'], // 站点管理用户审核规则
                    'systemRole' => $tenantUser['user_role'], // 登陆帐号全系统中的角色
                ];
                $cookie->expire = time() + 86400 * 365;
                Yii::$app->getResponse()->getCookies()->add($cookie);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取保存在 COOKIE 中的站点信息记录
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getTenantValue($key, $default = null)
    {
        $tenant = Yii::$app->getRequest()->getCookies()->get('_tenant');
        if ($tenant != null) {
            $value = $tenant->value;

            return isset($value[$key]) ? $value[$key] : $default;
        } else {
            return $default;
        }
    }

    /**
     * Get current language
     */
    public static function getLanguage()
    {
        return self::getTenantValue('language', Yii::$app->getRequest()->getPreferredLanguage(array_keys(self::getLanguages())));
    }

    public static function getTimezone()
    {
        return self::getTenantValue('timezone', Yii::$app->timeZone);
    }

    public static function getTenantId()
    {
        return self::getTenantValue('id', 0);
    }

    public static function getTenantName()
    {
        return self::getTenantValue('name');
    }

    /**
     * 用户系统角色
     *
     * @return mixed
     */
    public static function getUserRole()
    {
        return self::getTenantValue('systemRole');
    }

    /**
     * 租赁站点的用户角色
     *
     * @return mixed
     */
    public static function getTenantUserRole()
    {
        return self::getTenantValue('role');
    }

    /**
     * 租赁站点用户审核规则
     *
     * @return mixed
     */
    public static function getTenantUserRule()
    {
        return self::getTenantValue('rule');
    }

    /**
     * Return table name by special model name
     * For Example: Yad::modelName2TableName('app\models\news') return `news`, if
     * Use table prefix, will return `table_prifix_news` name
     *
     * @param string $modelName
     * @return string
     */
    public static function modelName2TableName($modelName)
    {
        $tableName = null;
        if (!empty($modelName)) {
            $tableName = (Yii::$app->getDb()->tablePrefix ?: '') . Inflector::camel2id(StringHelper::basename(BaseActiveRecord::id2ClassName($modelName)), '_');
        }

        return $tableName;
    }

    /**
     * 获取文本内容中的所有图片路径
     *
     * @param string $content
     * @param string|integer $order
     * @return array|string|null
     */
    public static function getTextImages($content, $order = 'ALL')
    {
        $images = null;
        if (!empty($content)) {
            $pattern = "/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
            preg_match_all($pattern, $content, $match);
            if (isset($match[1]) && !empty($match[1])) {
                if ($order === 'ALL') {
                    $images = $match[1];
                }
                if (is_numeric($order) && isset($match[1][$order - 1])) {
                    $images = $match[1][$order - 1];
                }
            }
        }

        return $images;
    }

}
