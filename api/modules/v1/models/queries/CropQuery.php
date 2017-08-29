<?php

namespace app\api\modules\v1\models\queries;

use app\api\modules\v1\models\Crop;
use app\api\modules\v1\models\Product;
use app\api\modules\v1\models\Warehouse;

/**
 * This is the ActiveQuery class for [[\app\api\modules\v1\models\Crop]].
 *
 * @see \app\api\modules\v1\models\Crop
 */
class CropQuery extends \yii\db\ActiveQuery
{
    /*
     * Только нужные поля
     */
    public function jsonData()
    {
        return $this->select([
            Crop::tableName() . '.id', 
            Crop::tableName() . '.title',
        ]);
    }
    
    /*
     * Сортировка
     */
    public function sorted()
    {
        return $this->orderBy([
            Crop::tableName() . '.sort' => SORT_ASC,
            Crop::tableName() . '.title' => SORT_ASC,
        ]);
    }
    
    /*
     * Только то, что нужно отображать
     */
    public function visible()
    {
        return $this->joinWith(['products product' => function ($pq) {
                $pq->joinWith('groups group');
                $pq->andWhere('group.id is NOT NULL');
                $pq->joinWith('prices price')
                    ->andWhere('price.price_status < ' . \app\api\modules\v1\models\Price::NONEED_NO_TAX);
            }])
            ->andWhere(['product.status' => Product::STATUS_ACTIVE]);
    }
    
    /*
     * Только по одной группе товаров (культуре)
     */
    public function only($cropId)
    {
        return $this->andWhere([Crop::tableName() . '.id' => $cropId]);
    }
    
    /*
     * С учетом приемки на склад
     */
    public function withWarehouses($warehouseId)
    {
        return $this->joinWith(['products pproduct' => function ($pq) use ($warehouseId) {
                $pq->joinWith('warehouses warehouse')
                    ->joinWith('groups group')
                    ->andWhere('group.id is NOT NULL')
                    ->andWhere(['warehouse.id' => $warehouseId])
                    ->andWhere(['warehouse.status' => Warehouse::STATUS_ACTIVE]);
            }])
            ->andWhere(['product.status' => Product::STATUS_ACTIVE]);
    }
    
    /**
     * Все записи
     * @return \app\api\modules\v1\models\Crop[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Одна запись
     * @return \app\api\modules\v1\models\Crop|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
