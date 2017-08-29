<?php

namespace app\api\modules\v1\models;

use app\modules\product\models\Price as BasePrice;
use yii\helpers\ArrayHelper;

use app\api\modules\v1\models\queries\PriceQuery;

/**
 * This is the model class for table "{{%price}}".
 *
 * @property integer $id
 * @property double $price_no_tax
 * @property double $price_with_tax
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property integer $price_status
 */

class Price extends BasePrice implements \app\interfaces\SiteDataInterface
{
    /*
     * Массив для хранения информации о менеджерах этой цены
     */
    public $manager;
    
    /**
     * Реализация интерфейса данных для сайта
     * @return array|[]
     */
    public static function getBaseData($data = [])
    {
        $prices = static::find()
                ->jsonData()
                ->hasManager()
                ->activeWarehouses()
                ->activeProducts()
                ->andWhere('price_status < ' . static::NONEED_NO_TAX)
                ->distinct();
        return $prices->all();
    }
    
    /**
     * @return PriceQuery
     */
    public static function find()
    {
        return new PriceQuery(get_called_class());
    }
    
    /*
     * Возвращаемые поля в REST API
     */
    public function fields()
    {
        $this->afterFind();
        return [
            'id',
            'price_no_tax' => function () {return $this->getPrice('no_tax');},
            'warehouse_id', 
            'product_id',
            'manager' => function () { return $this->fillManagers();},
        ];
    }
    
    /*
     * Возвращает массив объектов цен для прайслиста
     * Столбцы - склады, ряды - товары
     */
    public static function generateTable($prices, $warehouses, $products, $managers)
    {
        $warehousesId = ArrayHelper::getColumn($warehouses, 'id');
        $productsId = ArrayHelper::getColumn($products, 'id');
        //Пустой массив нужной размерности
        $tableRow = array_fill(0, count($productsId), '');
        $table = array_fill(0, count($warehousesId), $tableRow);
        //Заполняем данные по складам и товарам у менеджеров
        foreach ($managers as $value) {
            $value->checkWarehousesAndProducts(ArrayHelper::getColumn($warehouses, 'id'), ArrayHelper::getColumn($products, 'id'));
        }
        //Если цена есть и не равна значению "Не закупаем", то выводим ее в нужную позицию в массиве
        foreach ($prices as $price) {
            if ($price->price_status < Price::NONEED_NO_TAX) {
                $table[array_search($price->warehouse_id, $warehousesId)][array_search($price->product_id, $productsId)] = $price;
                //Для каждой цены добавляем массив данных по менеджеру
                $price->manager = $price->fillManagers();
            }
        }
        
        return $table;
    }

    /*
     * Формирование массива данных о менеджере
     */
    public function fillManagers()
    {
        $managerData = [];
        foreach ($this->users as $manager) {
            $managerData[] = [
                'name' => $manager->profile->name,
                'phone' => $manager->profile->phone,
                'email' => $manager->profile->work_email,
            ];
        }
        
        return $managerData;
    }
    
    /*
     * Поиск с параметрами для REST API
     */
    public static function findPricesWithParams($warehouses, $products)
    {
        $warehouseId = ArrayHelper::getColumn($warehouses, 'id');
        $productId = ArrayHelper::getColumn($products, 'id');
        
        $prices = Price::find()
                ->jsonData()
                ->with('users');
        
        //Фильтр по складам
        if ($warehouseId > 0) {
            $prices->andWhere([Price::tableName() . '.warehouse_id' => $warehouseId]);
        }
        //Фильтр по товарам
        if ($productId > 0) {
            $prices->andWhere([Price::tableName() . '.product_id' => $productId]);
        }
        
        return $prices->all();
    }
    
    /**
     * Возвращает все цены для группы
     * @param type $groupId
     * @return type
     */
    public static function findByGroup($groupId)
    {
        return Price::find()
                ->joinWith('product.groups group')
                ->where(['group.id' => $groupId]);
    }

    /*
     * Сбрасываем все поведения, отнаследованные от родителя
     */
    public function behaviors()
    {
        return [];
    }
}
