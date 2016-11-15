<?php

use yii\db\Migration;

/**
 * Handles the creation for table `news_content`.
 */
class m161024_132756_create_news_content_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%news_content}}', [
            'news_id' => $this->integer()->notNull()->comment('资讯编号'),
            'content' => $this->text()->notNull()->comment('正文'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%news_content}}');
    }

}
