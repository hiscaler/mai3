<?php

use yii\db\Migration;

class m151114_144627_create_product_content_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%product_content}}', [
            'product_id' => $this->integer()->notNull()->unique()->comment('商品 id'),
            'content' => $this->text()->notNull()->comment('商品详情'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%product_content}}');
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
