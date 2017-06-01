<?php

namespace app\modules\user\models\common;

use Yii;
use yii\db\ActiveRecord;
use app\modules\user\models\common\User;
use app\modules\user\models\common\query\ProfileQuery;
use app\modules\user\Module;
use app\modules\main\models\Group;
use app\modules\main\models\ProfileGroups;

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
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['work_email', 'email'],
            [['name', 'phone'], 'string', 'max' => 255],
            [['name', 'phone', 'work_email'], 'required'],
            [['user_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('user', 'USER_PROFILE_NAME'),
            'phone' => Module::t('user', 'USER_PROFILE_PHONE'),
            'work_email' => Module::t('user', 'USER_PROFILE_WORK_EMAIL'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\common\query\ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }
    
    /**
     * Prepare for SortableInput widget
     */
    public static function preparedForSIWActiveProfiles()
    {
        $all = Profile::find()
                ->joinWith('user')
                ->where([User::tableName() . '.status' => User::STATUS_ACTIVE])
                ->all();

        $result = [];
        foreach ($all as $profile){
            $result[$profile->id] = ['content' => $profile->name . ' (' . $profile->phone . ')'];
        }
        
        return $result;
    }
    
    /**
     * List of User Groups
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(ProfileGroups::tableName(), ['profile_id' => 'id']);
    }
    
    /**
     * Get Group Titles array
     */
    public function getGroupsTitleArray()
    {
        $result = [];
        foreach ($this->groups as $group){
            $result[] = $group->title;
        }
        return $result;
    }
}
