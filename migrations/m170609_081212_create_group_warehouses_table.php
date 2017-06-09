<?php

use yii\db\Migration;

/**
 * Handles the creation of table `group_warehouses`.
 */
class m170609_081212_create_group_warehouses_table extends Migration
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
        $this->createTable('{{%group_warehouses}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->notNull(),
            'warehouse_id' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk-group-to-group_warehouses', '{{%group_warehouses}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-warehouse-to-group_warehouses', '{{%group_warehouses}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%group_warehouses}}');
    }
}
