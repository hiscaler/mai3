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
            'key' => $this->string(20)->notNull()->unique()->comment('站点 key'),
            'name' => $this->string(20)->notNull()->comment('站点名称'),
            'language' => $this->string(10)->notNull()->comment('语言'),
            'timezone' => $this->string(20)->notNull()->comment('时区'),
            'date_format' => $this->string(20)->notNull()->comment('日期格式'),
            'time_format' => $this->string(20)->notNull()->comment('时间格式'),
            'datetime_format' => $this->string(20)->notNull()->comment('日期时间格式'),
            'domain_name' => $this->string(100)->notNull()->comment('域名'),
            'description' => $this->text()->comment('描述'),
            'status' => $this->boolean()->defaultValue(1)->notNull()->comment('状态'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
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
