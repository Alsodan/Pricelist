<?php

use yii\db\Migration;

class m170728_101943_add_group_users_table_rule_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%group_users}}', 'rule', $this->boolean());
 
        $this->update('{{%group_users}}', ['rule' => false]);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%group_users}}', 'rule');

        return false;
    }
}
