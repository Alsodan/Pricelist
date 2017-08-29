<?php

namespace app\api\modules\v1\models\queries;

use app\api\modules\v1\models\Region;

/**
 * This is the ActiveQuery class for [[\app\api\modules\v1\models\Region]].
 *
 * @see \app\api\modules\v1\models\Region
 */
class RegionQuery extends \yii\db\ActiveQuery
{
    /*
     * Только нужные поля
     */
    public function jsonData()
    {
        return $this->select([
            Region::tableName() . '.id', 
            Region::tableName() . '.title',
        ]);
    }

    /*
     * Сортировка
     */
    public function sorted()
    {
        return $this->orderBy([
            Region::tableName() . '.sort' => SORT_ASC,
            Region::tableName() . '.title' => SORT_ASC,
        ]);
    }
    
    /*
     * Только активные
     */
    public function active()
    {
        return $this->andWhere([Region::tableName() . '.status' => Region::STATUS_ACTIVE]);
    }

    /**
     * Все записи
     * @return \app\modules\region\models\Region[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Одна запись
     * @return \app\modules\region\models\Region|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
