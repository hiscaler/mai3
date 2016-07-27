<?php

use yii\db\Migration;

/**
 * 商品表
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m151114_042119_create_item_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull()->defaultValue(0)->comment('分类 id'),
            'type_id' => $this->integer()->notNull()->defaultValue(0)->comment('类型 id'),
            'brand_id' => $this->integer()->notNull()->defaultValue(0)->comment('品牌 id'),
            'sn' => $this->string(16)->notNull()->comment('编码'),
            'name' => $this->string(50)->notNull()->comment('品名'),
            'market_price' => $this->integer()->notNull()->comment('市场价'),
            'shop_price' => $this->integer()->notNull()->comment('商城价'),
            'member_price' => $this->integer()->notNull()->comment('会员价'),
            'picture_path' => $this->string(100)->comment('大图'),
            'keywords' => $this->string(100)->notNull()->comment('关键词'),
            'description' => $this->text()->comment('描叙'),
            'ordering' => $this->integer()->notNull()->defaultValue(0)->comment('排序'),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0)->comment('点击次数'),
            'sales_count' => $this->integer()->notNull()->defaultValue(0)->comment('销售数量'),
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
        $this->dropTable('{{%item}}');
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
