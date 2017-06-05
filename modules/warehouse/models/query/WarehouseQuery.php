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
        return $this->andWhere(['status' => Warehouse::STATUS_ACTIVE]);
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
