<?php

use yii\db\Migration;

/**
 * Handles the creation of table `organization`.
 */
class m170630_120900_create_organization_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%organization}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'file' => $this->string(),
            'sort' => $this->integer(),
            'status' => $this->integer(),
            'warehouse_id' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk-organization-to-warehouse', '{{%organization}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%organization}}');
    }
}
