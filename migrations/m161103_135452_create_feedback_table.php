<?php

use yii\db\Migration;

/**
 * Handles the creation for table `feedback`.
 */
class m161103_135452_create_feedback_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%feedback}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->smallInteger()->notNull()->defaultValue(0)->comment('分组'),
            'username' => $this->string(20)->notNull()->comment('姓名'),
            'tel' => $this->string(13)->comment('联系电话'),
            'email' => $this->string(50)->comment('邮箱'),
            'title' => $this->string(100)->notNull()->comment('咨询标题'),
            'message' => $this->text()->notNull()->comment('咨询内容'),
            'ip_address' => $this->integer()->notNull()->comment('IP 地址'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'tenant_id' => $this->integer()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('提交时间'),
            'created_by' => $this->integer()->notNull()->comment('提交人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%feedback}}');
    }

}
