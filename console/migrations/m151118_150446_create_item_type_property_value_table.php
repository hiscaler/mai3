<?php

use yii\db\Migration;

/**
 * 商品类型属性值
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151118_150446_create_item_type_property_value_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_type_property_value}}', [
            'property' => $this->integer()->notNull(),
            'value' => $this->string(60)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_type_property_value}}');
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
