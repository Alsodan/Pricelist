<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile_products`.
 */
class m170602_123951_create_profile_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%profile_products}}', [
            'profile_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-profile_products-profile_id', '{{%profile_products}}', 'profile_id', false);
        $this->createIndex('idx-profile_products-product_id', '{{%profile_products}}', 'product_id', false);
        $this->createIndex('idx-profile_products-id', '{{%profile_products}}', ['profile_id', 'product_id'], true);
        
        $this->addForeignKey('fk-profile-to-profile_products', '{{%profile_products}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-product-to-profile_products', '{{%profile_products}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%profile_products}}');
    }
}
