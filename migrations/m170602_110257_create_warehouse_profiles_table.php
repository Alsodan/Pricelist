<?php

use yii\db\Migration;

/**
 * Handles the creation of table `warehouse_profiles`.
 */
class m170602_110257_create_warehouse_profiles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%warehouse_profiles}}', [
            'warehouse_id' => $this->integer()->notNull(),
            'profile_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-warehouse_profiles-warehouse_id', '{{%warehouse_profiles}}', 'warehouse_id', false);
        $this->createIndex('idx-warehouse_profiles-profile_id', '{{%warehouse_profiles}}', 'profile_id', false);
        $this->createIndex('idx-warehouse_profiles-id', '{{%warehouse_profiles}}', ['warehouse_id', 'profile_id'], true);
        
        $this->addForeignKey('fk-warehouse-to-warehouse_profiles', '{{%warehouse_profiles}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-profile-to-warehouse_profiles', '{{%warehouse_profiles}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%warehouse_profiles}}');
    }
}
