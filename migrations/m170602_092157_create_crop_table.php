<?php

use yii\db\Migration;

/**
 * Handles the creation of table `crop`.
 */
class m170602_092157_create_crop_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%crop}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'group_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-crop-to-product', '{{%crop}}', 'id', '{{%product}}', 'crop_id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%crop}}');
    }
}
