<?php

namespace app\api\modules\v1\models\queries;

use app\api\modules\v1\models\User;
use app\modules\product\models\PriceUsers;

/**
 * This is the ActiveQuery class for [[app\api\modules\v1\models\User]].
 *
 * @see app\api\modules\v1\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*
     * Менеджеры цен
     */
    public function price($prices)
    {
        return $this->joinWith('prices')->andWhere([PriceUsers::tableName() . '.price_id' => $prices]);
    }

    /*
     * Только активные
     */
    public function active()
    {
        return $this->andWhere([User::tableName() . '.status' => User::STATUS_ACTIVE]);
    }

    /**
     * Все записи
     * @return \app\api\modules\v1\models\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Одна запись
     * @return \app\api\modules\v1\models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
