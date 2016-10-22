<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ad_space`.
 */
class m161007_085231_create_ad_space_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%ad_space}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->smallInteger()->notNull()->defaultValue(0)->comment('分组 id'),
            'alias' => $this->string(100)->notNull()->comment('别名'),
            'name' => $this->string(100)->notNull()->comment('广告位名称'),
            'description' => $this->string(255)->notNull()->comment('描述'),
            'width' => $this->smallInteger()->notNull()->defaultValue(0)->comment('宽度'),
            'height' => $this->smallInteger()->notNull()->defaultValue(0)->comment('高度'),
            'ads_count' => $this->integer()->notNull()->defaultValue(0)->comment('广告数量'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'tenant_id' => $this->smallInteger()->notNull()->comment('所属站点'),
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
        $this->dropTable('{{%ad_space}}');
    }

}
