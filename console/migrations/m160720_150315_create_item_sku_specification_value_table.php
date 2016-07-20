<?php

use yii\db\Migration;

/**
 * 单品规格属性值关联表
 */
class m160720_150315_create_item_sku_specification_value_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%item_sku_specification_value}}', [
            'id' => $this->primaryKey(),
            'sku_id' => $this->integer()->notNull(),
            'specfication_value_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%item_sku_specification_value}}');
    }

}
