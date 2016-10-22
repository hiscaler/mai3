<?php

use yii\db\Migration;

/**
 * 订单地址
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160815_141318_create_order_address_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%order_address}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull()->comment('订单 id'),
            'linkman' => $this->string(20)->notNull()->comment('联系人'),
            'address' => $this->string(60)->notNull()->comment('地址'),
            'tel' => $this->string(15)->notNull()->comment('电话'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%order_address}}');
    }

}
