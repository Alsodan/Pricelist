<?php

use yii\db\Migration;

class m170621_120139_add_user_table_role_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(64));
 
        $this->update('{{%user}}', ['role' => 'roleUser']);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'role');

        return false;
    }
}
