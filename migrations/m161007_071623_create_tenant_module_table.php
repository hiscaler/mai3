<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tenant_module`.
 */
class m161007_071623_create_tenant_module_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%tenant_module}}', [
            'tenant_id' => $this->integer()->notNull()->comment('所属站点'),
            'module_name' => $this->string(30)->notNull()->comment('模块'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%tenant_module}}');
    }

}
