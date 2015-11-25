<?php

use yii\db\Migration;

/**
 * 商品类型和规格关联表
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151118_143657_create_type_specification_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%type_specification}}', [
            'type_id' => $this->integer()->notNull(),
            'specification_id' => $this->integer()->notNull(),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%type_specification}}');
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
