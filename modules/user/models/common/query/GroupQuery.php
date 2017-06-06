<?php

namespace app\modules\user\models\common\query;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\common\Group]].
 *
 * @see \app\modules\user\models\common\Group
 */
class GroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\user\models\common\Group[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\common\Group|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
