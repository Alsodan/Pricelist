<?php

namespace app\modules\region\models\query;

use app\modules\region\models\Region;

/**
 * This is the ActiveQuery class for [[\app\modules\region\models\Region]].
 *
 * @see \app\modules\region\models\Region
 */
class RegionQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere([Region::tableName() . '.status' => Region::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\region\models\Region[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\region\models\Region|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
