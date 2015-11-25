<?php

use yii\db\Migration;

/**
 * 商品类型属性
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151118_150436_create_type_property_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%type_property}}', [
            'type_id' => $this->integer()->notNull(),
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
        $this->dropTable('{{%type_property}}');
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
