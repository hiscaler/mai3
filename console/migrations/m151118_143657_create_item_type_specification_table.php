<?php

use yii\db\Schema;
use yii\db\Migration;

class m151118_143657_create_item_type_specification_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_type_specification}}', [
            'item_type_id' => $this->integer()->notNull(),
            'specification_id' => $this->integer()->notNull(),
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
