<?php

use yii\db\Migration;

/**
 * 实体数据标签
 */
class m151110_141038_create_entity_label_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%entity_label}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer()->notNull(),
            'entity_name' => $this->string(20)->notNull(),
            'label_id' => $this->integer()->notNull(),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
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
        $this->dropTable('{{%entity_label}}');
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
