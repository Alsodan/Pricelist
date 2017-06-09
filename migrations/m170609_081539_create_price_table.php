<?php

use yii\db\Migration;

/**
 * Handles the creation of table `price`.
 */
class m170609_081539_create_price_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%price}}', [
            'id' => $this->primaryKey(),
            'price_no_tax' => $this->decimal(10, 2),
            'price_with_tax' => $this->decimal(10, 2),
            'warehouse_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk-product-to-price', '{{%price}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-warehouse-to-price', '{{%price}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%price}}');
    }
}
