<?php

namespace app\modules\group\models;

use app\modules\group\Module;
use \app\modules\user\models\common\query\GroupQuery;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\Profile;
use app\modules\group\models\ProfileGroups;
use \app\modules\user\models\common\User;
use \app\components\behaviors\ManyHasManyBehavior;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 */
class Group extends \yii\db\ActiveRecord
{
    //Group Activity
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            ['profilesList', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('group', 'GROUP_ID'),
            'title' => Module::t('group', 'GROUP_TITLE'),
            'status' => Module::t('group', 'GROUP_STATUS'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'profiles' => 'profilesList',                   
                ],
            ],
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->profilesList = $this->profilesList;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
    }
    
    /**
     * Change Group activity
     * @return boolean
     */
    public function changeStatus(){
        $this->active = !$this->active;
        return $this->save(false);
    }
    
    /**
     * Get Group Activity names array
     * 
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_DISABLED => Module::t('group', 'GROUP_ACTIVITY_DISABLED'),
            self::STATUS_ACTIVE => Module::t('group', 'GROUP_ACTIVITY_ACTIVE'),
        ];
    }
    
    /**
     * Get Group activity name
     * 
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusArray(), $this->status);
    }
    
    /**
     * Get Profiles
     * 
     * @return array profiles
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['id' => 'profile_id'])
            ->viaTable(ProfileGroups::tableName(), ['group_id' => 'id']);
    }
    
    /**
     * Get only active Profiles
     * 
     * @return array profiles
     */
    public function getActiveProfiles()
    {
        $result = [];
        foreach ($this->profiles as $profile) {
            if ($profile->user->status == User::STATUS_ACTIVE) {
                $result[] = $profile;
            }
        }
        
        return $result;
    }

    /**
     * Get only active Profiles string
     * 
     * @return array profiles
     */
    public function getProfilesAsStringArray()
    {
        $result = [];
        foreach ($this->activeProfiles as $profile) {
            $result[$profile->id] = $profile->name . ' (' . $profile->phone . ')';
        }
        
        return $result;
    }    
    
    /**
     * Get Profiles Name and Phone as string
     * 
     * @return array profiles data
     */
    public function preparedForSIWActiveProfiles()
    {
        $result = [];
        foreach ($this->activeProfiles as $profile) {
            $result[$profile->id] = ['content' => $profile->name . ' (' . $profile->phone . ')'];
        }
        
        return $result;
    }
    
    
    /**
     * Get Groups Dropdown
     */
    public static function getGroupsDropdown()
    {
        $result = [];
        foreach (self::find()->all() as $group){
            $result[$group->id] = $group->title;
        }
        return $result;
    }    
}
