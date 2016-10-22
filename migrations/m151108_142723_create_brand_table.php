<?php

use yii\db\Migration;

class m151108_142723_create_brand_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%brand}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(20)->notNull()->unique()->comment('品牌别名'),
            'name' => $this->string(20)->notNull()->unique()->comment('品牌名称'),
            'icon_path' => $this->string(100)->comment('图标'),
            'description' => $this->text()->comment('描述'),
            'ordering' => $this->integer()->notNull()->defaultValue(0)->comment('排序'),
            'tenant_id' => $this->integer()->notNull()->comment('所属站点'),
            'enabled' => $this->boolean()->defaultValue(1)->notNull()->comment('激活'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
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
