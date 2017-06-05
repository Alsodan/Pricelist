<?php

use yii\db\Migration;

/**
 * Handles the creation of table `warehouse_groups`.
 */
class m170602_095106_create_warehouse_groups_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%warehouse_groups}}', [
            'warehouse_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-warehouse_groups-warehouse_id', '{{%warehouse_groups}}', 'warehouse_id', false);
        $this->createIndex('idx-warehouse_groups-group_id', '{{%warehouse_groups}}', 'group_id', false);
        $this->createIndex('idx-warehouse_groups-id', '{{%warehouse_groups}}', ['warehouse_id', 'group_id'], true);
        
        $this->addForeignKey('fk-warehouse-to-warehouse_groups', '{{%warehouse_groups}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-group-to-warehouse_groups', '{{%warehouse_groups}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%warehouse_groups}}');
    }
}
