<?php

namespace app\modules\warehouse\models\query;

use app\modules\warehouse\models\Warehouse;

/**
 * This is the ActiveQuery class for [[\app\modules\warehouse\models\Warehouse]].
 *
 * @see \app\modules\warehouse\models\Warehouse
 */
class WarehouseQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere([Warehouse::tableName() . '.status' => Warehouse::STATUS_ACTIVE]);
    }

    public function sorted()
    {
        return $this->orderBy([
            Warehouse::tableName() . '.sort' => SORT_ASC,
            Warehouse::tableName() . '.title' => SORT_ASC,
        ]);
    }
    
    public function visible()
    {
        return $this->joinWith('groups group')
            ->andWhere('group.id is NOT NULL');
    }
    
    /**
     * @inheritdoc
     * @return \app\modules\warehouse\models\Warehouse[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\warehouse\models\Warehouse|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
