<?php

use yii\db\Migration;

/**
 * Handles the creation for table `shopping_cart`.
 */
class m160814_150654_create_shopping_cart_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%shopping_cart}}', [
            'item_id' => $this->integer()->notNull()->comment('单品 id'),
            'quantity' => $this->smallInteger()->notNull()->defaultValue(1)->comment('数量'),
            'created_at' => $this->integer()->notNull()->comment('订购时间'),
            'created_by' => $this->integer()->notNull()->comment('订购人'),
        ]);

        $this->createIndex('item_id_created_by', '{{%shopping_cart}}', ['item_id', 'created_by']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%shopping_cart}}');
    }

}
