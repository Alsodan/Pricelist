<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile_groups`.
 */
class m170530_072511_create_profile_groups_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%profile_groups}}', [
            'profile_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-profile_groups-profile_id', '{{%profile_groups}}', 'profile_id', false);
        $this->createIndex('idx-profile_groups-group_id', '{{%profile_groups}}', 'group_id', false);
        $this->createIndex('idx-profile_groups-id', '{{%profile_groups}}', ['profile_id', 'group_id'], true);
        
        $this->addForeignKey('fk-profile-to-profile_groups', '{{%profile_groups}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-group-to-profile_groups', '{{%profile_groups}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%profile_groups}}');
    }
}
