<?php

namespace app\modules\user\models\common\query;
 
use app\modules\user\models\common\User;
use yii\db\ActiveQuery;
 
class UserQuery extends ActiveQuery
{
    public function overdue($timeout)
    {
        return $this
            ->andWhere(['status' => User::STATUS_WAIT])
            ->andWhere(['<', 'created_at', time() - $timeout]);
    }
}