<?php

use yii\db\Migration;

/**
 * 订单详情
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160815_132823_create_order_detail_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%order_detail}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull()->comment('订单 id'),
            'item_id' => $this->integer()->notNull()->comment('单品 id'),
            'quantity' => $this->smallInteger()->notNull()->comment('数量'),
            'price' => $this->decimal(10, 2)->notNull()->comment('单价'),
            'discount_amount' => $this->decimal(7, 2)->notNull()->defaultValue(0)->comment('优惠金额'),
            'express_fee' => $this->decimal(7, 2)->notNull()->defaultValue(0)->comment('运费'),
            'subtotal_amount' => $this->decimal(10, 2)->notNull()->comment('小计'),
            'remark' => $this->string(255)->comment('备注'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%order_detail}}');
    }

}
