<?php

use yii\db\Migration;

/**
 * 商品类型和规格关联表
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151118_143657_create_item_type_specification_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_type_specification}}', [
            'item_type_id' => $this->integer()->notNull(),
            'specification_id' => $this->integer()->notNull(),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_type_specification}}');
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
