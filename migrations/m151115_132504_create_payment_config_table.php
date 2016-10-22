<?php

use yii\db\Schema;
use yii\db\Migration;

class m151115_132504_create_payment_config_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%payment_config}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(16)->notNull(),
            'name' => $this->string(100)->notNull(),
            'config' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'tenant_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%payment_config}}');
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
