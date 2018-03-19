<?php

use yii\db\Migration;

/**
 * 收货地址
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class m180319_140501_create_address_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%address}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(30)->notNull()->comment('地址别名'),
            'consignee' => $this->string(30)->notNull()->comment('收货人'),
            'address' => $this->string(60)->notNull()->comment('地址'),
            'mobile_phone' => $this->string(11)->comment('手机号码'),
            'tel' => $this->string(13)->comment('固定电话'),
            'email' => $this->string(100)->comment('电子邮箱'),
            'default' => $this->boolean()->notNull()->defaultValue(0)->comment('默认地址'),
            'member_id' => $this->integer()->notNull()->comment('所属会员'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%address}}');
    }
}
