<?php

namespace app\api\modules\v1\models\queries;

use app\api\modules\v1\models\Price;

/**
 * This is the ActiveQuery class for [[\app\api\modules\v1\models\Price]].
 *
 * @see \app\api\modules\v1\models\Price
 */
class PriceQuery extends \yii\db\ActiveQuery
{
    /*
     * Только нужные поля
     */
    public function jsonData()
    {
        return $this->select([
                Price::tableName() . '.id',
                Price::tableName() . '.price_no_tax',
                Price::tableName() . '.price_status',
                Price::tableName() . '.warehouse_id',
                Price::tableName() . '.product_id'
            ]);
    }
    
    /*
     * Только те цены, у которых есть менеджеры
     */
    public function hasManager()
    {
        return $this->joinWith(['users user' => function ($uq) {
                        $uq->joinWith('groups user_group')
                            ->with('profile');
                    }])
                    ->andWhere('user.id is NOT NULL')
                    ->andWhere('user.status = ' . \app\api\modules\v1\models\User::STATUS_ACTIVE)
                    ->andWhere('user_group.id is NOT NULL')
                    ->andWhere('user_group.status = ' . \app\modules\group\models\Group::STATUS_ACTIVE);
    }
    
    /*
     * Только по активным складам
     */
    public function activeWarehouses()
    {
        return $this->joinWith('warehouse warehouse')
                ->andWhere('warehouse.status = ' . \app\api\modules\v1\models\Warehouse::STATUS_ACTIVE);
    }
    
    /*
     * Только по активным товарам
     */
    public function activeProducts()
    {
        return $this->joinWith('product product')
                ->andWhere('product.status = ' . \app\api\modules\v1\models\Product::STATUS_ACTIVE);
    }
    
    /**
     * Все записи
     * @return \app\modules\product\models\Price[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Одна запись
     * @return \app\modules\product\models\Price|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
