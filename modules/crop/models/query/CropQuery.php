<?php

namespace app\modules\crop\models\query;

use app\modules\crop\models\Crop;

/**
 * This is the ActiveQuery class for [[\app\modules\crop\models\Crop]].
 *
 * @see \app\modules\crop\models\Crop
 */
class CropQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Crop::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\crop\models\Crop[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\crop\models\Crop|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
