<?php

use yii\db\Migration;

/**
 * 商品类型
 */
class m151118_134346_create_item_type_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
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
        $this->dropTable('{{%item_type}}');
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
