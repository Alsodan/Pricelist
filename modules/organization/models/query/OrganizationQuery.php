<?php

namespace app\modules\organization\models\query;

use app\modules\organization\models\Organization;

/**
 * This is the ActiveQuery class for [[\app\modules\organization\models\Organization]].
 *
 * @see \app\modules\organization\models\Organization
 */
class OrganizationQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere([Organization::tableName() . '.status' => Organization::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\organization\models\Organization[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\organization\models\Organization|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
