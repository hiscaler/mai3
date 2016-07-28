<?php

use yii\db\Migration;

/**
 * 单品规格属性值关联表
 */
class m160720_150315_create_item_specification_value_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_specification_value}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'specification_value_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_specification_value}}');
    }

}
