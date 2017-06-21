<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_groups`.
 */
class m170607_075608_create_product_groups_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%product_groups}}', [
            'group_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-profile_products-group_id', '{{%product_groups}}', 'group_id', false);
        $this->createIndex('idx-profile_products-product_id', '{{%product_groups}}', 'product_id', false);
        $this->createIndex('idx-profile_products-id', '{{%product_groups}}', ['group_id', 'product_id'], true);
        
        $this->addForeignKey('fk-group-to-product_groups', '{{%product_groups}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-product-to-product_groups', '{{%product_groups}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product_groups}}');
    }
}
