<?php

use yii\db\Migration;

/**
 * 规格定义表
 */
class m151117_111214_create_specification_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%specification}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1),
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
        $this->dropTable('{{%specification}}');
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
