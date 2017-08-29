<?php

namespace app\api\modules\v1\models;

use app\modules\user\models\common\Profile as BaseProfile;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $work_email
 * @property integer $user_id
 *
 * @property User $user
 */
class Profile extends BaseProfile
{

}
