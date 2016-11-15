<?php

use yii\db\Migration;

/**
 * Handles the creation for table `news`.
 */
class m161024_131937_create_news_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull()->comment('所属分类'),
            'title' => $this->string()->notNull()->comment('标题'),
            'short_title' => $this->string()->notNull()->comment('短标题'),
            'tags' => $this->string()->comment('标签'),
            'keywords' => $this->string()->comment('关键词'),
            'author' => $this->string(30)->notNull()->comment('作者'),
            'source' => $this->string(30)->notNull()->comment('来源'),
            'description' => $this->text()->comment('简介'),
            'is_picture_news' => $this->boolean()->notNull()->defaultValue(0)->comment('是否图片新闻'),
            'picture_path' => $this->string(100)->comment('图片'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'enabled_comment' => $this->boolean()->notNull()->defaultValue(1)->comment('激活评论'),
            'comments_count' => $this->integer()->notNull()->defaultValue(0)->comment('评论数量'),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0)->comment('点击次数'),
            'up_count' => $this->integer()->notNull()->defaultValue(0)->comment('顶'),
            'down_count' => $this->integer()->notNull()->defaultValue(0)->comment('踩'),
            'ordering' => $this->integer()->notNull()->defaultValue(0)->comment('排序'),
            'published_at' => $this->integer()->notNull()->comment('发布时间'),
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
        $this->dropTable('{{%news}}');
    }

}
