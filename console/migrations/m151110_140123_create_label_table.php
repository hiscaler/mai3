<?php

use yii\db\Migration;

/**
 * 推送位管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151110_140123_create_label_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%label}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(20)->notNull(),
            'frequency' => $this->integer()->notNull()->defaultValue(0),
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
        $this->dropTable('{{%label}}');
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
