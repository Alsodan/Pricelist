<?php

use yii\db\Migration;

/**
 * Handles the creation of table `warehouse_products`.
 */
class m170602_123702_create_warehouse_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%warehouse_products}}', [
            'warehouse_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-warehouse_products-warehouse_id', '{{%warehouse_products}}', 'warehouse_id', false);
        $this->createIndex('idx-warehouse_products-product_id', '{{%warehouse_products}}', 'product_id', false);
        $this->createIndex('idx-warehouse_products-id', '{{%warehouse_products}}', ['warehouse_id', 'product_id'], true);
        
        $this->addForeignKey('fk-warehouse-to-warehouse_products', '{{%warehouse_products}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-product-to-warehouse_products', '{{%warehouse_products}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%warehouse_products}}');
    }
}
