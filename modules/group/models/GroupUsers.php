<?php

namespace app\modules\group\models;

use \yii\db\ActiveRecord;
use app\modules\group\models\Group;
use app\modules\user\models\common\User;

/**
 * This is the model class for table "{{%group_users}}".
 *
 * @property integer $profile_id
 * @property integer $group_id
 *
 * @property Group $group
 * @property Profile $profile
 */
class GroupUsers extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'group_id'], 'integer'],
            [['user_id', 'group_id'], 'unique', 'targetAttribute' => ['user_id', 'group_id'], 'message' => 'The combination of User ID and Group ID has already been taken.'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['rule'], 'boolean'],
            [['rule'], 'default', 'value' => true],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
