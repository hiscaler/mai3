<?php

use yii\db\Migration;

/**
 * Handles the creation for table `download`.
 */
class m160808_113757_create_download_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%download}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull()->comment('标题'),
            'path_type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('地址类型'),
            'url_path' => $this->string(200)->comment('外部地址'),
            'file_path' => $this->string(100)->comment('文件'),
            'cover_photo_path' => $this->string(100)->comment('封面图片'),
            'keywords' => $this->string(100)->comment('关键词'),
            'description' => $this->text()->comment('描述'),
            'pay_credits' => $this->integer()->notNull()->defaultValue(0)->comment('消耗积分'),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0)->comment('点击次数'),
            'downloads_count' => $this->integer()->notNull()->defaultValue(0)->comment('下载次数'),
            'enabled' => $this->boolean()->defaultValue(1)->notNull()->comment('状态'),
            'tenant_id' => $this->smallInteger()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
            'deleted_at' => $this->integer()->comment('删除时间'),
            'deleted_by' => $this->integer()->comment('删除人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%download}}');
    }

}
