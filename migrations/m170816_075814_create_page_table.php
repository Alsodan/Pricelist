<?php

use yii\db\Migration;

/**
 * Handles the creation of table `page`.
 */
class m170816_075814_create_page_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%page}}', [
            'id' => $this->string()->notNull()->unique(),
            'title' => $this->string(),
            'meta_description' => $this->string(),
            'meta_keywords' => $this->string(),
            'header' => $this->string(),
            'subheader' => $this->text(),
            'content' => $this->text()
        ], $tableOptions);
        
        $this->addPrimaryKey('id', '{{%page}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
