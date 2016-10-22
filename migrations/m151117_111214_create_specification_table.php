<?php

use yii\db\Migration;

/**
 * 规格定义表
 */
class m151117_111214_create_specification_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%specification}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->comment('规格名称'),
            'type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('显示类型'),
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
        $this->dropTable('{{%specification}}');
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
