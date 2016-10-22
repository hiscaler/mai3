<?php

use yii\db\Migration;

/**
 * 单品表
 */
class m160720_122704_create_item_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()->defaultValue(0)->comment('分类 id'),
            'brand_id' => $this->integer()->notNull()->defaultValue(0)->comment('品牌 id'),
            'sn' => $this->string(20)->notNull()->unique()->comment('单品编码'),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'market_price' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('市场价'),
            'shop_price' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('市场价'),
            'member_price' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('会员价'),
            'cost_price' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('成本价'),
            'picture_path' => $this->string(100)->comment('主图'),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0)->comment('查看次数'),
            'favorites_count' => $this->integer()->notNull()->defaultValue(0)->comment('收藏数量'),
            'sales_count' => $this->integer()->notNull()->defaultValue(0)->comment('销售数量'),
            'stocks_count' => $this->integer()->notNull()->defaultValue(0)->comment('库存数量'),
            'default' => $this->boolean()->notNull()->defaultValue(0)->comment('默认'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'online' => $this->boolean()->notNull()->defaultValue(1)->comment('上架'),
            'on_off_datetime' => $this->integer()->comment('上下架时间'),
            'view_require_credits' => $this->integer()->comment('上架时间'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'tenant_id' => $this->integer()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item}');
    }

}
