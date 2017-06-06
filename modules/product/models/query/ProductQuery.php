<?php

namespace app\modules\product\models\query;

use app\modules\product\models\Product;

/**
 * This is the ActiveQuery class for [[\app\modules\product\models\Product]].
 *
 * @see \app\modules\product\models\Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Product::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\product\models\Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\product\models\Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
