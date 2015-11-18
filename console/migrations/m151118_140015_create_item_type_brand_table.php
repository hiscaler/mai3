<?php

use yii\db\Migration;

/**
 * 商品类型和品牌关联表
 */
class m151118_140015_create_item_type_brand_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_type_brand}}', [
            'item_type_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_type_brand}}');
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
