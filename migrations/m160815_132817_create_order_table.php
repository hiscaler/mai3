<?php

use yii\db\Migration;

/**
 * 订单
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160815_132817_create_order_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'sn' => $this->string(32)->notNull()->comment('订单号'),
            'quantity' => $this->smallInteger()->notNull()->comment('数量'),
            'total_amount' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('总价'),
            'discount_amount' => $this->decimal(7, 2)->notNull()->defaultValue(0)->comment('优惠金额'),
            'express_fee' => $this->decimal(7, 2)->notNull()->defaultValue(0)->comment('运费'),
            'actual_amount' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('实际金额'),
            'address_id' => $this->integer()->notNull()->defaultValue(0)->comment('收货地址'),
            'express_id' => $this->string(12)->comment('快递公司'),
            'express_sn' => $this->string(15)->comment('快递号'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'remark' => $this->string(255)->comment('备注'),
            'tenant_id' => $this->integer()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('下单时间'),
            'created_by' => $this->integer()->notNull()->comment('下单人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%order}}');
    }

}
