<?php

use yii\db\Migration;

/**
 * Handles the creation for table `article`.
 */
class m160806_142419_create_article_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(30)->notNull()->comment('别名'),
            'title' => $this->string(100)->notNull()->comment('标题'),
            'keywords' => $this->string(100)->comment('关键词'),
            'description' => $this->text()->comment('描述'),
            'content' => $this->text()->notNull()->comment('正文'),
            'picture_path' => $this->string(100)->comment('图片'),
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
        $this->dropTable('{{%article}}');
    }

}
