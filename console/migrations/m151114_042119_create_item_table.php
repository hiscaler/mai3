<?php

use yii\db\Migration;

/**
 * 商品表
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151114_042119_create_item_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull()->defaultValue(0),
            'brand_id' => $this->integer()->notNull()->defaultValue(0),
            'sn' => $this->string(16)->notNull(),
            'name' => $this->string(50)->notNull(),
            'market_price' => $this->integer()->notNull(),
            'shop_price' => $this->integer()->notNull(),
            'member_price' => $this->integer()->notNull(),
            'picture_path' => $this->string(100),
            'keywords' => $this->string(100)->notNull(),
            'description' => $this->text(),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0),
            'sales_count' => $this->integer()->notNull()->defaultValue(0),
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
        $this->dropTable('{{%item}}');
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
