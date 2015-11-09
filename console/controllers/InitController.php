<?php

namespace console\controllers;

use common\models\User;
use PDO;
use Yii;
use yii\base\Security;
use yii\console\Controller;

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

    public function actionIndex()
    {
        $this->_initAdminUser();
    }

}
