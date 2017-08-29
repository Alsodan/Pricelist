<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m170720_094405_create_log_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB ROW_FORMAT=COMPACT';
        }
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'date' => $this->timestamp(),
            'obj_type' => $this->string(),
            'obj_id' => $this->integer(),
            'user_id' => $this->integer(),
            'field' => $this->string(),
            'old_value' => $this->text(),
            'new_value' => $this->text()
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
