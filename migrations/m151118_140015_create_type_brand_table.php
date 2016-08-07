<?php

use yii\db\Migration;

/**
 * 商品类型和品牌关联表
 */
class m151118_140015_create_type_brand_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%type_brand}}', [
            'type_id' => $this->integer()->notNull()->comment('商品类型 id'),
            'brand_id' => $this->integer()->notNull()->comment('品牌 id'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%type_brand}}');
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
