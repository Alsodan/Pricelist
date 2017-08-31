<?php

namespace app\api\modules\v1\models;

use app\modules\product\models\Product as BaseProduct;
use app\api\modules\v1\models\queries\ProductQuery;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $crop_id
 * @property integer $grade
 * @property string $title
 * @property string $subtitle
 * @property string $specification
 * @property integer $status
 */
class Product extends BaseProduct
{
    /*
     * Возвращаемые поля в REST API
     */
    public function fields()
    {
        return [
            'id',
            'title' => function () {return $this->title . ($this->subtitle ? ('<br>(' . $this->subtitle . ')') : '');},
            'specification'
        ];
    }
    
    /**
     * Реализация интерфейса данных для сайта
     * @return array|[]
     */
    public static function getBaseData($data = [])
    {
        /*if ($data['cookie']['warehouse'] != 0 || $data['cookie']['crop'] != 0 || $data['cookie']['region'] != 0) {
            $warehouses = Warehouse::findWarehousesWithParams($data['cookie']['warehouse'], $data['cookie']['crop'], $data['cookie']['region']);
            $products = Product::findProductsWithParams($warehouses, $data['cookie']['crop']);
            
            return $products;
        } else {*/
            $productIds = array_unique(\yii\helpers\ArrayHelper::getColumn($data['prices'], 'product_id'));

            $products = Product::find()
                    ->jsonData()
                    ->visible()
                    ->sorted()
                    ->andWhere([Product::tableName() . '.id' => $productIds]);

            return $products->all();
        //}
    }
    
    /*
     * Поиск с параметрами для REST API
     * Сложновато сделать мелкими функциями, т.к. не нужны множественные JOIN одинаковых таблиц
     */
    public static function findProductsWithParams($warehouses, $cropId = 0)
    {
        $warehouseId = \yii\helpers\ArrayHelper::getColumn($warehouses, 'id');
        
        $products = Product::find()
                ->jsonData()
                ->active()
                ->sorted()
                ->distinct()
                ->joinWith('groups group')
                ->joinWith('prices price')
                ->leftJoin(\app\modules\product\models\PriceUsers::tableName() . ' price_user', 'price.id = price_user.price_id')
                ->leftJoin(User::tableName() . ' user', 'price_user.user_id = user.id')
                ->leftJoin(Warehouse::tableName() . ' warehouse', 'price.warehouse_id = ' . 'warehouse.id')
                ->andWhere('group.id is NOT NULL')
                ->andWhere('price.price_status < ' . Price::NONEED_NO_TAX)
                ->andWhere(['warehouse.id' => $warehouseId])
                ->andWhere(['warehouse.status' => Warehouse::STATUS_ACTIVE])
                ->andWhere('user.id is NOT NULL');
        
        //Фильтр по группе товаров (культуре)
        if ($cropId > 0) {
            $products->only($cropId);
        }
        
        return $products->all();
    }
    
    /**
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
}
