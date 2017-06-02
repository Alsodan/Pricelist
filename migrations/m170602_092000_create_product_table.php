<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m170602_092000_create_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'crop_id' => $this->integer(),
            'grade' => $this->integer(),
            'title' => $this->string(100)->notNull(),
            'subtitle' => $this->string(100),
            'specification' => $this->text(),
            'price_no_tax' => $this->decimal(10, 2),
            'price_with_tax' => $this->decimal(10, 2),
            'status' => $this->integer(),
        ]);
        
        $this->createIndex('idx-crop-id', '{{%product}}', 'crop_id', false);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product}}');
    }
}
