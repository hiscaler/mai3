<?php

use yii\db\Migration;

/**
 * Handles the creation for table `friendly_link`.
 */
class m161007_092850_create_friendly_link_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%friendly_link}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->smallInteger()->notNull()->defaultValue(0)->comment('分组'),
            'type' => $this->boolean()->notNull()->defaultValue(0)->comment('类型'),
            'title' => $this->string(60)->notNull()->comment('名称'),
            'description' => $this->string(100)->notNull()->comment('描述'),
            'url' => $this->string(100)->notNull()->comment('链接地址'),
            'url_open_target' => $this->string(6)->notNull()->defaultValue('_blank')->comment('链接打开方式'),
            'logo_path' => $this->string(100)->comment('图标'),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(0)->comment('排序'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'tenant_id' => $this->smallInteger()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%friendly_link}}');
    }

}
