<?php

use yii\db\Migration;

/**
 * Handles the creation of table `crop`.
 */
class m170602_092000_create_crop_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%crop}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%crop}}');
    }
}
