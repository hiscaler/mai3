<?php

use yii\db\Migration;

/**
 * 商品类型
 */
class m151118_134346_create_type_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('类型名称'),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1)->comment('排序'),
            'status' => $this->boolean()->notNull()->defaultValue(1)->comment('状态'),
            'tenant_id' => $this->integer()->notNull()->comment('站点 id'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%type}}');
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
