<?php

use yii\db\Migration;

/**
 * 租赁站点管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151110_112841_create_tenant_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(20)->notNull(),
            'language' => $this->string(10)->notNull(),
            'timezone' => $this->string(20)->notNull(),
            'date_format' => $this->string(20)->notNull(),
            'time_format' => $this->string(20)->notNull(),
            'datetime_format' => $this->string(20)->notNull(),
            'domain_name' => $this->string(100)->notNull(),
            'description' => $this->text(),
            'status' => $this->boolean()->defaultValue(1)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tenant}}');
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
