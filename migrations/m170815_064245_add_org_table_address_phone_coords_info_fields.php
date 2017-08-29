<?php

use yii\db\Migration;

class m170815_064245_add_org_table_address_phone_coords_info_fields extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%organization}}', 'address', $this->string(255));
        $this->addColumn('{{%organization}}', 'phone', $this->string(100));
        $this->addColumn('{{%organization}}', 'latitude', $this->decimal(9, 6));
        $this->addColumn('{{%organization}}', 'longitude', $this->decimal(9, 6));
        $this->addColumn('{{%organization}}', 'info', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%organization}}', 'role');

        return false;
    }
}
