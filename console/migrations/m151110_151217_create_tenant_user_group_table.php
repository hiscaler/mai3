<?php

use yii\db\Migration;

/**
 * 站点用户分组
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151110_151217_create_tenant_user_group_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_user_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
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
        $this->dropTable('{{%tenant_user_group}}');
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
