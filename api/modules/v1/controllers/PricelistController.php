<?php

namespace app\api\modules\v1\controllers;

use yii\rest\Controller;
use app\api\modules\v1\models\Price;
use app\api\modules\v1\models\Warehouse;
use app\api\modules\v1\models\Crop;
use app\api\modules\v1\models\Product;
use app\api\modules\v1\models\User;
use app\api\modules\v1\models\Region;
use yii\helpers\ArrayHelper;

class PricelistController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter' ] = [
              'class' => \yii\filters\Cors::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        return $behaviors;
    }

    /*
     * Список складов
     * Фильтры: склад, группа товаров (культура), группа складов (регион)
     */
    public function actionWarehouses($warehouseId = 0, $cropId = 0, $regionId = 0)
    {
        $result = Warehouse::findWarehousesWithParams($warehouseId, $cropId, $regionId);
        $regions = Region::findWithParams(array_unique(ArrayHelper::getColumn($result, 'region_id')));

        return empty($result) ? ['error' => 'There is no warehouses', 'data' => ''] : ['error' => '', 'data' => $result, 'region' => $regions];
    }

    /*
     * Список товаров
     * Фильтры: склад, группа товаров (культура)
     */
    public function actionProducts($warehouseId = 0, $cropId = 0)
    {
        $result = Product::findProductsWithParams($warehouseId, $cropId);
        
        return empty($result) ? ['error' => 'There is no products', 'data' => ''] : ['error' => '', 'data' => $result];
    }
    
    /*
     * Список групп товаров (культура)
     * Фильтры: склад, группа товаров (культура)
     */
    public function actionCrops($warehouseId = 0, $cropId = 0)
    {
        $result = Crop::findCropsWithParams($warehouseId, $cropId);
        
        return empty($result) ? ['error' => 'There is no crops', 'data' => ''] : ['error' => '', 'data' => $result];
    }

    /*
     * Таблица цен
     * Фильтры: склад, группа товаров (культура), группа складов (регион)
     */
    public function actionPrices($warehouseId = 0, $cropId = 0, $regionId = 0)
    {
        $warehouses = Warehouse::findWarehousesWithParams($warehouseId, $cropId, $regionId);
        $products = Product::findProductsWithParams($warehouses, $cropId);
        $prices = Price::findPricesWithParams($warehouses, $products);

        if (empty($warehouses) || empty($products) || empty($prices)) {
            return ['error' => 'There is no prices', 'data' => '', 'change' => \app\components\behaviors\models\Changes::getLastChangeDate(),];
        }
        
        $managers = User::findByPrices(ArrayHelper::getColumn($prices, 'id'));
        $result = Price::generateTable($prices, $warehouses, $products, $managers);
        
        //TODO Некрасиво - убрать в модель, переделать алгоритм
        //Убираем склады, в которых сейчас не закупают товар
        $whCount = count($warehouses);
        $prodCount = count($products);
        for ($i = 0; $i < $whCount; $i++) {
            $emptyCount = 0;
            foreach ($result[$i] as $item) {
                if (empty($item)) {
                    $emptyCount++;
                }
            }
            if ($emptyCount == $prodCount) {
                unset($result[$i]);
                unset($warehouses[$i]);
            }
        }
        
        return [
            'error' => '',
            'data' => array_values($result),
            'warehouses' => array_values($warehouses), 
            'products' => $products,
            'managers' => $managers,
            'change' => \app\components\behaviors\models\Changes::getLastChangeDate(),
        ];
    }
}
