<?php

namespace app\modules\main\models;

use \yii\db\ActiveRecord;
use app\modules\main\models\Group;
use app\modules\user\models\common\Profile;

/**
 * This is the model class for table "{{%profile_groups}}".
 *
 * @property integer $profile_id
 * @property integer $group_id
 *
 * @property Group $group
 * @property Profile $profile
 */
class ProfileGroups extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile_groups}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', 'group_id'], 'integer'],
            [['profile_id', 'group_id'], 'unique', 'targetAttribute' => ['profile_id', 'group_id'], 'message' => 'The combination of Profile ID and Group ID has already been taken.'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
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
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }
}
