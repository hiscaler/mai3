<?php

use yii\db\Migration;

/**
 * 商品图片表
 */
class m151114_152655_create_item_image_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_image}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'url' => $this->string(100),
            'path' => $this->string(100),
            'description' => $this->string(50)->notNull(),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_image}}');
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
