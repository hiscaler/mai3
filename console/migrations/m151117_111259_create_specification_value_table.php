<?php

use yii\db\Migration;

/**
 * 规格值
 */
class m151117_111259_create_specification_value_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%specification_value}}', [
            'id' => $this->primaryKey(),
            'specification_id' => $this->integer()->notNull(),
            'text' => $this->string(30)->notNull(),
            'icon_path' => $this->string(100),
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
        $this->dropTable('{{%specification_value}}');
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
