<?php

namespace app\api\modules\v1\models;

use app\modules\warehouse\models\Warehouse as BaseWarehouse;
use app\api\modules\v1\models\queries\WarehouseQuery;

/**
 * This is the model class for table "{{%warehouse}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 */
class Warehouse extends BaseWarehouse implements \app\interfaces\SiteDataInterface
{
    /**
     * Реализация интерфейса данных для сайта
     * @return array|[]
     */
    public static function getBaseData($data = [])
    {
        $warehouseIds = array_unique(\yii\helpers\ArrayHelper::getColumn($data['prices'], 'warehouse_id'));

        $warehouses = Warehouse::find()
                ->jsonData()
                ->visible()
                ->sorted()
                ->active()
                ->with('activeOrganizations')
                ->andWhere([Warehouse::tableName() . '.id' => $warehouseIds]);

        return $warehouses->all();
    }
    
    /*
     * Возвращаемые поля в REST API
     */
    public function fields() {
        return [
            'id',
            'title',
            'organizations' => function () { return $this->activeOrganizations;},
        ];
    }
    
    /**
     * @return WarehouseQuery
     */
    public static function find()
    {
        return new WarehouseQuery(get_called_class());
    }
    
    /*
     * Поиск с параметрами для REST API
     */
    public static function findWarehousesWithParams($warehouseId = 0, $cropId = 0, $regionId = 0)
    {
        $warehouses = Warehouse::find()
                ->jsonData()
                ->active()
                ->visible()
                ->sorted()
                ->with('organizations');
        
        //Фильтр по складам
        if ($warehouseId > 0) {
            $warehouses->only($warehouseId);
        }
        //Фильтр по группам товаров (культурам)
        if ($cropId > 0) {
            $warehouses->withProducts($cropId);
        }
        //Фильтр по регионам
        if ($regionId > 0) {
            $warehouses->withRegion($regionId);
        }
        
        return $warehouses->all();
    }
}
