<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ad`.
 */
class m161007_085237_create_ad_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%ad}}', [
            'id' => $this->primaryKey(),
            'space_id' => $this->integer()->notNull()->comment('广告位 id'),
            'name' => $this->string(100)->notNull()->comment('广告名称'),
            'url' => $this->string(200)->notNull()->comment('广告 URL'),
            'type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('广告类型'),
            'file_path' => $this->string(100)->notNull()->comment('广告文件'),
            'text' => $this->text()->comment('广告消息'),
            'begin_datetime' => $this->integer()->notNull()->comment('开始时间'),
            'end_datetime' => $this->integer()->notNull()->comment('结束时间'),
            'message' => $this->text()->comment('过期提示消息'),
            'views_count' => $this->integer()->notNull()->defaultValue(0)->comment('查看次数'),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0)->comment('点击次数'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%ad}}');
    }

}
