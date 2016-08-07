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
            'sn' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(50)->notNull(),
            'market_price' => $this->integer()->notNull(), // 市场价
            'member_price' => $this->integer()->notNull(), // 会员价
            'cost_price' => $this->integer()->notNull(), // 成本价
            'picture_path' => $this->string(100),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0), // 查看数量
            'favorites_count' => $this->integer()->notNull()->defaultValue(0), // 收藏数量
            'sales_count' => $this->integer()->notNull()->defaultValue(0), // 销售数量
            'stocks_count' => $this->integer()->notNull()->defaultValue(0), // 库存数量
            'default' => $this->boolean()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item}');
    }

}
