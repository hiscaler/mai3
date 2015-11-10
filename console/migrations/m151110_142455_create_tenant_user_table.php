<?php

use yii\db\Migration;

/**
 * 站点用户
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151110_142455_create_tenant_user_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_user}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->integer()->notNull()->defaultValue(0),
            'rule_id' => $this->integer()->notNull()->defaultValue(0),
            'user_group_id' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'tenant_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tenant_user}}');
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
