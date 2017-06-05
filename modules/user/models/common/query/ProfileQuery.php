<?php

namespace app\modules\user\models\common\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\common\Profile]].
 *
 * @see \app\modules\user\models\common\Profile
 */
class ProfileQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\user\models\common\Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\common\Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
