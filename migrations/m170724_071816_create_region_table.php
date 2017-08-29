<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region`.
 */
class m170724_071816_create_region_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%region}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'sort' => $this->integer(),
            'status' => $this->integer(),
        ], $tableOptions);
        
        $this->addColumn('{{%warehouse}}', 'region_id', $this->integer());

    }

    public function safeDown()
    {
        $this->dropTable('{{%region}}');
    }
}
