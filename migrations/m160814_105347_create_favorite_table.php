<?php

use yii\db\Migration;

/**
 * 收藏夹
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160814_105347_create_favorite_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull()->comment('单品 id'),
            'created_at' => $this->integer()->notNull()->comment('收藏时间'),
            'created_by' => $this->integer()->notNull()->comment('收藏人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%favorite}}');
    }

}
