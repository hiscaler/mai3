<?php

use yii\db\Migration;

class m151114_144627_create_item_content_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_content}}', [
            'item_id' => $this->integer()->notNull()->unique(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_content}}');
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
