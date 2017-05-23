<?php

namespace app\modules\user\models\query;
 
use app\modules\user\models\User;
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