<?php

use yii\db\Migration;

/**
 * 系统用户表
 */
class m151106_155959_create_user_table extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(20)->notNull()->unique(),
            'nickname' => $this->string(20)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string(50)->notNull()->unique(),
            'role' => $this->smallInteger()->notNull()->defaultValue(0),
            'register_ip' => $this->integer()->notNull(),
            'login_count' => $this->integer()->notNull()->defaultValue(10),
            'last_login_ip' => $this->integer()->defaultValue(null),
            'last_login_time' => $this->integer()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
