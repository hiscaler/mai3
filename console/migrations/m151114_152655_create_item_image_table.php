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
            'item_id' => $this->integer()->notNull()->comment('商品 id'),
            'url' => $this->string(100)->comment('外部 URL 地址'),
            'path' => $this->string(100)->comment('图片'),
            'description' => $this->string(50)->notNull()->comment('描述'),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1)->comment('排序'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
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
