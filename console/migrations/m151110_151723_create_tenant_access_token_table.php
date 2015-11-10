<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * 用户站点访问令牌
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151110_151723_create_tenant_access_token_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_access_token}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'access_token' => $this->string(32)->notNull(),
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
        $this->dropTable('{{%tenant_access_token}}');
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
