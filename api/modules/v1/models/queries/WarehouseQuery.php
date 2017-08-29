<?php

namespace app\api\modules\v1\models\queries;

use app\api\modules\v1\models\Warehouse;
use app\api\modules\v1\models\Product;
use app\api\modules\v1\models\Price;

/**
 * This is the ActiveQuery class for [[\app\api\modules\v1\models\Warehouse]].
 *
 * @see \app\api\modules\v1\models\Warehouse
 */
class WarehouseQuery extends \yii\db\ActiveQuery
{
    /*
     * Только нужные поля
     */
    public function jsonData()
    {
        return $this->select([
            Warehouse::tableName() . '.id', 
            Warehouse::tableName() . '.title', 
            Warehouse::tableName() . '.region_id',
        ]);
    }

    /*
     * Только активные
     */
    public function active()
    {
        return $this->andWhere([Warehouse::tableName() . '.status' => Warehouse::STATUS_ACTIVE]);
    }

    /*
     * Сортировка
     */
    public function sorted()
    {
        return $this->orderBy([
            Warehouse::tableName() . '.sort' => SORT_ASC,
            Warehouse::tableName() . '.title' => SORT_ASC,
        ]);
    }

    /*
     * Только то, что нужно отображать
     */
    public function visible()
    {
        return $this->joinWith('groups group')
            ->andWhere('group.id is NOT NULL')
            ->joinWith('prices price')
            ->andWhere('price.price_status < ' . Price::NONEED_NO_TAX);
    }

    /*
     * Только определенные склады
     */
    public function only($warehouseId)
    {
        return $this->andWhere([Warehouse::tableName() . '.id' => $warehouseId]);
    }

    /*
     * Только с определенными группами товаров (культурами)
     */
    public function withProducts($cropId)
    {
        return $this->joinWith(['products product' => function ($pq) {
                $pq->joinWith('groups product_group')
                    ->andWhere('product_group.id is NOT NULL');
            }])
            ->andWhere(['product.crop_id' => $cropId])
            ->andWhere(['product.status' => Product::STATUS_ACTIVE]);
    }

    /*
     * Только содержащие товары у которых есть менеджеры
     */
    public function withUsers()
    {
        return $this->joinWith(['prices price' => function ($wpq) {
                $wpq->joinWith('users price_user')
                    ->andWhere('price_user.id is NOT NULL');
            }]);
    }
    
    /*
     * Только по определенному региону
     */
    public function withRegion($regionId)
    {
        return $this->andWhere([Warehouse::tableName() . '.region_id' => $regionId]);
    }

    /**
     * Все записи
     * @return \app\api\modules\v1\models\Warehouse[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Одна запись
     * @return \app\api\modules\v1\models\Warehouse|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
