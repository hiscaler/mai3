<?php

use yii\db\Migration;

/**
 * 站点用户分组
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151110_151217_create_tenant_user_group_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_user_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('组名'),
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
        $this->dropTable('{{%tenant_user_group}}');
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
