<?php

use yii\db\Migration;

/**
 * Handles the creation of table `price_users`.
 */
class m170609_090828_create_price_users_table extends Migration
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
        $this->createTable('{{%price_users}}', [
            'id' => $this->primaryKey(),
            'price_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk-price-to-price_users', '{{%price_users}}', 'price_id', '{{%price}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-user-to-price_users', '{{%price_users}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%price_users}}');
    }
}
