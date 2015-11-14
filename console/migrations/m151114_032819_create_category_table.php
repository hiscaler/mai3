<?php

use yii\db\Migration;

/**
 * 分类管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151114_032819_create_category_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'alias' => $this->string(20)->notNull(),
            'name' => $this->string(30)->notNull(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'level' => $this->smallInteger()->notNull()->defaultValue(0),
            'parent_ids' => $this->string(100),
            'parent_names' => $this->string(255),
            'icon_path' => $this->string(100),
            'description' => $this->text(),
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
        $this->dropTable('{{%category}}');
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
