<?php

namespace app\modules\product\models;

use \yii\db\ActiveRecord;
use app\modules\warehouse\models\Warehouse;
use app\modules\product\models\Product;

/**
 * This is the model class for table "{{%warehouse_products}}".
 *
 * @property integer $product_id
 * @property integer $warehouse_id
 *
 * @property Warehouse $warehouse
 * @property Product $product
 */
class WarehouseProducts extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse_products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'warehouse_id'], 'integer'],
            [['product_id', 'warehouse_id'], 'unique', 'targetAttribute' => ['product_id', 'warehouse_id'], 'message' => 'The combination of Product ID and Warehouse ID has already been taken.'],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
