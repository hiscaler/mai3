<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_142723_create_brand_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%brand}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(20)->notNull()->unique(),
            'icon_path' => $this->string(100),
            'description' => $this->text(),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
            'tenant_id' => $this->integer()->notNull(),
            'status' => $this->boolean()->defaultValue(1)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%brand}}');
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
