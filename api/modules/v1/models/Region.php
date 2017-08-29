<?php

namespace app\api\modules\v1\models;

use app\api\modules\v1\models\queries\RegionQuery;
use app\modules\region\models\Region as BaseRegion;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 */
class Region extends BaseRegion implements \app\interfaces\SiteDataInterface
{
    /**
     * Реализация интерфейса данных для сайта
     * @return array|[]
     */
    public static function getBaseData($data = [])
    {
        $regionIds = array_unique(\yii\helpers\ArrayHelper::getColumn($data['warehouses'], 'region_id'));

        $regions = Region::find()
                ->jsonData()
                ->active()
                ->sorted()
                ->andWhere([Region::tableName() . '.id' => $regionIds]);
        
        return $regions->all();
        
    }
    
    /*
     * Поиск с параметрами для REST API
     */
    public static function findWithParams($regionsIds)
    {
        $regions = Region::find()
                ->where(['id' => $regionsIds])
                ->jsonData()
                ->active()
                ->sorted();
        
        return $regions->all();
    }
    
    /*
     * Возвращаемые поля в REST API
     */
    public function fields()
    {
        return [
            'id',
            'title',
        ];
    }
    
    /**
     * @return RegionQuery
     */
    public static function find()
    {
        return new RegionQuery(get_called_class());
    }
}
