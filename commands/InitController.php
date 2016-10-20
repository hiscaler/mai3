<?php

namespace app\commands;

use app\models\Constant;
use app\models\Lookup;
use app\models\User;
use PDO;
use Yii;
use yii\base\Security;
use yii\console\Controller;
use yii\helpers\Inflector;

/**
 * 初始化数据
 */
class InitController extends Controller
{

    /**
     * 初始化默认管理用户
     * @return int
     */
    private function _initAdminUser()
    {
        $username = 'admin';
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT COUNT(*) FROM {{%user}} WHERE username = :username');
        $command->bindValue(':username', $username, PDO::PARAM_STR);
        $exist = $command->queryScalar();
        if (!$exist) {
            $now = time();
            $security = new Security;
            $columns = [
                'username' => $username,
                'nickname' => 'admin',
                'auth_key' => $security->generateRandomString(),
                'password_hash' => $security->generatePasswordHash('admin'),
                'password_reset_token' => null,
                'email' => 'admin@example.com',
                'role' => 10,
                'register_ip' => '::1',
                'login_count' => 0,
                'last_login_ip' => null,
                'last_login_time' => null,
                'status' => User::STATUS_ACTIVE,
                'created_by' => 0,
                'created_at' => $now,
                'updated_by' => 0,
                'updated_at' => $now,
            ];
            $db->createCommand()->insert('{{%user}}', $columns)->execute();
        } else {
            echo "'{$username}' is exists.";
        }

        return 0;
    }

    /**
     * 初始化配置资料
     * @return int
     */
    public function _initLookups()
    {
        echo "Begin..." . PHP_EOL;
        $items = [
            Lookup::GROUP_SEO => [
                'as.meta.keywords' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => null,
                ],
                'as.meta.description' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXTAREA,
                    'value' => null,
                ],
            ],
            Lookup::GROUP_SYSTEM => [
                'as.site.name' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => null,
                ],
                'as.site.icp' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => null,
                ],
                'as.site.statistics-code' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXTAREA,
                    'value' => null,
                ],
                // 是否激活安全选项
                'as.security.enable' => [
                    'returnType' => Lookup::RETURN_TYPE_BOOLEAN,
                    'inputMethod' => Lookup::INPUT_METHOD_CHECKBOX,
                    'value' => true,
                ],
                'as.security.change-password-interval-days' => [
                    'returnType' => Lookup::RETURN_TYPE_INTEGER,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => 30,
                ],
                'as.offline' => [
                    'returnType' => Lookup::RETURN_TYPE_BOOLEAN,
                    'inputMethod' => Lookup::INPUT_METHOD_CHECKBOX,
                    'value' => false,
                ],
                'as.offline.message' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXTAREA,
                    'value' => null,
                ],
                'as.language' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_DROPDOWNLIST,
                    'inputValue' => implode(PHP_EOL, [
                        'en-US:en-US',
                        'zh-CN:zh-CN',
                        'zh-TW:zh-TW',
                    ]),
                    'value' => 'zh-CN',
                ],
                'as.timezone' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_DROPDOWNLIST,
                    'inputValue' => implode(PHP_EOL, [
                        'Etc/GMT:Etc/GMT',
                        'Etc/GMT+0:Etc/GMT+0',
                        'Etc/GMT+1:Etc/GMT+1',
                        'Etc/GMT+10:Etc/GMT+10',
                        'Etc/GMT+11:Etc/GMT+11',
                        'Etc/GMT+12:Etc/GMT+12',
                        'Etc/GMT+2:Etc/GMT+2',
                        'Etc/GMT+3:Etc/GMT+3',
                        'Etc/GMT+4:Etc/GMT+4',
                        'Etc/GMT+5:Etc/GMT+5',
                        'Etc/GMT+6:Etc/GMT+6',
                        'Etc/GMT+7:Etc/GMT+7',
                        'Etc/GMT+8:Etc/GMT+8',
                        'Etc/GMT+9:Etc/GMT+9',
                        'Etc/UTC:Etc/UTC',
                        'PRC:PRC',
                    ]),
                    'value' => 'PRC',
                ],
                'as.date-format' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => 'php:Y-m-d',
                ],
                'as.time-format' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => 'php:H:i:s',
                ],
                'as.datetime-format' => [
                    'returnType' => Lookup::RETURN_TYPE_STRING,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => 'php:Y-m-d H:i:s',
                ],
                // 用户注册默认状态
                'as.user.signup.status' => [
                    'returnType' => Lookup::RETURN_TYPE_INTEGER,
                    'inputMethod' => Lookup::INPUT_METHOD_DROPDOWNLIST,
                    'inputValue' => implode(PHP_EOL, [
                        User::STATUS_PENDING . ':' . Yii::t('user', 'Pending'),
                        User::STATUS_ACTIVE . ':' . Yii::t('user', 'Active'),
                    ]),
                    'value' => User::STATUS_PENDING,
                ],
                // 用户注册赠送积分
                'as.user.signup.credits' => [
                    'returnType' => Lookup::RETURN_TYPE_INTEGER,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => 0,
                ],
                // 用户推荐注册赠送积分
                'as.user.signup.referral.credits' => [
                    'returnType' => Lookup::RETURN_TYPE_INTEGER,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXT,
                    'value' => 0,
                ],
                // 模块设置
                'm.models.friendly-link.group' => [
                    'returnType' => Lookup::RETURN_TYPE_ARRAY,
                    'inputMethod' => Lookup::INPUT_METHOD_TEXTAREA,
                    'value' => [],
                ]
            ],
        ];
        $tenantId = 0;
        $db = Yii::$app->getDb();
        $cmd = $db->createCommand();
        $existsCmd = $db->createCommand('SELECT COUNT(*) FROM {{%lookup}} WHERE [[type]] = :type AND [[label]] = :label AND [[tenant_id]] = :tenantId');
        $now = time();
        foreach ($items as $group => $data) {
            foreach ($data as $label => $item) {
                $type = isset($item['type']) ? $item['type'] : Lookup::TYPE_PUBLIC;
                $label = trim($label);
                // Check exists, ignore it if exists.
                $exists = $existsCmd->bindValues([
                        ':type' => $type,
                        ':label' => $label,
                        ':tenantId' => $tenantId
                    ])->queryScalar();
                if ($exists) {
                    echo "{$label} is exists, ignore it..." . PHP_EOL;
                    continue;
                }

                echo "Insert {$label} ..." . PHP_EOL;
                $index = strpos($label, '.');
                if ($index !== false && in_array(substr($label, 0, $index), ['as', 'm'])) {
                    $description = substr($label, $index + 1);
                }
                $description = Inflector::camel2words($label, '.');
                $columns = [
                    'type' => $type,
                    'group' => $group,
                    'label' => $label,
                    'description' => $description,
                    'value' => serialize(isset($item['value']) ? $item['value'] : ''),
                    'return_type' => isset($item['returnType']) ? $item['returnType'] : Lookup::RETURN_TYPE_STRING,
                    'input_method' => isset($item['inputMethod']) ? $item['inputMethod'] : Lookup::INPUT_METHOD_TEXT,
                    'input_value' => isset($item['inputValue']) ? $item['inputValue'] : '',
                    'enabled' => Constant::BOOLEAN_TRUE,
                    'tenant_id' => $tenantId,
                    'created_by' => 0,
                    'created_at' => $now,
                    'updated_by' => 0,
                    'updated_at' => $now,
                ];
                $cmd->insert('{{%lookup}}', $columns)->execute();
            }
        }
        echo "Done..." . PHP_EOL;

        return 0;
    }

    public function actionIndex()
    {
        $this->_initAdminUser();
        $this->_initLookups();
    }

}
