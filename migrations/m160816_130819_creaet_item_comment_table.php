<?php

use yii\db\Migration;

/**
 * 商品评论
 */
class m160816_130819_creaet_item_comment_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_comment}}', [
            'id' => $this->primaryKey(),
            'level' => $this->smallInteger()->defaultValue(0)->notNull()->comment('等级'),
            'item_id' => $this->integer()->notNull()->comment('单品 id'),
            'username' => $this->string(20)->comment('姓名'),
            'tel' => $this->string(15)->comment('电话'),
            'email' => $this->string(60)->comment('邮箱'),
            'message' => $this->text()->notNull()->comment('内容'),
            'return_user_id' => $this->integer()->notNull()->defaultValue(0)->comment('回复人'),
            'return_datetime' => $this->integer()->notNull()->defaultValue(0)->comment('回复人'),
            'return_message' => $this->text()->comment('回复内容'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'ip_address' => $this->integer()->notNull()->comment('IP 地址'),
            'tenant_id' => $this->integer()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('评论时间'),
            'created_by' => $this->integer()->notNull()->comment('会员'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_comment}}');
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
