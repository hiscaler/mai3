<?php

use yii\db\Migration;

/**
 * 商品类型属性
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151118_150436_create_type_property_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%type_property}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('商品类型 id'),
            'name' => $this->string(30)->notNull()->comment('属性名称'),
            'return_type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('返回值类型'),
            'input_method' => $this->string(12)->notNull()->comment('录入方式'),
            'input_values' => $this->text()->comment('录入选项'),
            'input_default_value' => $this->string()->comment('默认值'),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(1)->comment('排序'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%type_property}}');
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
