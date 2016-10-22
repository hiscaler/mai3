<?php

use yii\db\Migration;

/**
 * 规格值
 */
class m151117_111259_create_specification_value_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%specification_value}}', [
            'id' => $this->primaryKey(),
            'specification_id' => $this->integer()->notNull()->comment('规格 id'),
            'text' => $this->string(30)->notNull()->comment('规格值名称'),
            'icon_path' => $this->string(100)->comment('规格图标'),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1)->comment('排序'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'tenant_id' => $this->integer()->notNull()->comment('站点 id'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%specification_value}}');
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
