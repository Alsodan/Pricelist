<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m170602_092001_create_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'crop_id' => $this->integer(),
            'grade' => $this->integer(),
            'title' => $this->string(100)->notNull(),
            'subtitle' => $this->string(100),
            'specification' => $this->text(),
            'status' => $this->integer(),
        ]);
        
        $this->createIndex('idx-crop-id', '{{%product}}', 'crop_id', false);
        $this->addForeignKey('fk-product-to-crop', '{{%product}}', 'crop_id', '{{%crop}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
