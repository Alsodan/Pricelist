<?php

namespace app\api\modules\v1\models;

use app\modules\crop\models\Crop as BaseCrop;
use app\api\modules\v1\models\queries\CropQuery;

/**
 * This is the model class for table "{{%crop}}".
 *
 * @property integer $id
 * @property string $title
 */
class Crop extends BaseCrop implements \app\interfaces\SiteDataInterface
{
    /**
     * Реализация интерфейса данных для сайта
     * Выборка данных на сайт
     * @return array|[]
     */
    public static function getBaseData($data = [])
    {
        /*if ($data['cookie']['warehouse'] != 0 || $data['cookie']['crop'] != 0 || $data['cookie']['region'] != 0) {
            $crops = static::findCropsWithParams($data['cookie']['warehouse'], $data['cookie']['crop']);
            
            return $crops;
        } else {*/
            /*$cropIds = array_unique(\yii\helpers\ArrayHelper::getColumn($data['products'], 'crop_id'));*/
            $crops = Crop::find()
                    ->jsonData()
                    ->sorted()
                    ->visible()
                    /*->where(['id' => $cropIds])*/;

            return $crops->all();
        //}
    }
    
    /*
     * Поиск с параметрами для REST API
     */
    public static function findCropsWithParams($warehouseId = 0, $cropId = 0)
    {
        $crops = Crop::find()
                ->jsonData()
                ->visible()
                ->sorted()
                ->distinct();
        
        //Фильтр по группе товаров (культуре)
        if ($cropId > 0) {
            $crops->only($cropId);
        }
        //Фильтр по складам
        if ($warehouseId > 0) {
            $crops->withWarehouses($warehouseId);
        }
        
        return $crops->all();
    }
    
    /**
     * @return CropQuery
     */
    public static function find()
    {
        return new CropQuery(get_called_class());
    }

}
