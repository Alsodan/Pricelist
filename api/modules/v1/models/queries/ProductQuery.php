<?php

namespace app\api\modules\v1\models\queries;

use app\api\modules\v1\models\Product;
use app\api\modules\v1\models\Warehouse;

/**
 * This is the ActiveQuery class for [[\app\api\modules\v1\models\Product]].
 *
 * @see \app\api\modules\v1\models\Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    /*
     * Только нужные поля
     */
    public function jsonData()
    {
        return $this->select([
            Product::tableName() . '.id',
            Product::tableName() . '.title',
            Product::tableName() . '.subtitle',
            Product::tableName() . '.specification',
            Product::tableName() . '.crop_id',
        ]);
    }

    /*
     * Только активные товары
     */
    public function active()
    {
        return $this->andWhere([Product::tableName() . '.status' => Product::STATUS_ACTIVE]);
    }

    /*
     * Сортировка
     */
    public function sorted()
    {
        return $this->orderBy([
            Product::tableName() . '.sort' => SORT_ASC,
            Product::tableName() . '.title' => SORT_ASC,
        ]);
    }

    /*
     * Только то, что нужно отображать
     */
    public function visible()
    {
        return $this->joinWith('groups group')
            ->andWhere('group.id is NOT NULL');
    }

    /*
     * Только по одной группе товаров (культуре)
     */
    public function only($cropId)
    {
        return $this->andWhere([Product::tableName() . '.crop_id' => $cropId]);
    }

    /*
     * С учетом приемки на склад
     */
    public function withWarehouses($warehouseId)
    {
        return $this->joinWith(['warehouses warehouse' => function ($wq) {
                $wq->joinWith('groups warehouse_group')
                    ->andWhere('warehouse_group.id is NOT NULL');
            }])
            ->andWhere(['warehouse.id' => $warehouseId])
            ->andWhere(['warehouse.status' => Warehouse::STATUS_ACTIVE]);
    }
    
    /*
     * С учетом наличия менеджера
     */
    public function withUsers()
    {
        return $this->joinWith(['prices price' => function ($ppq) {
                $ppq->joinWith('users user')
                    ->andWhere('user.id is NOT NULL');
            }]);
    }
    
    /**
     * Все записи
     * @return \app\modules\product\models\Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Одна запись
     * @return \app\modules\product\models\Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
