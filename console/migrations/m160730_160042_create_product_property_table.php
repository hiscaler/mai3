<?php

use yii\db\Migration;

/**
 * 商品属性
 */
class m160730_160042_create_product_property_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%product_property}}', [
            'product_id' => $this->integer()->notNull()->comment('商品 id'),
            'property_id' => $this->integer()->notNull()->comment('属性 id'),
            'value' => $this->text()->notNull()->comment('属性值'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product_property}}');
    }

}
