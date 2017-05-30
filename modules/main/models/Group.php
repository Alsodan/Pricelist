<?php

namespace app\modules\main\models;

use app\modules\main\Module;
use \app\modules\user\models\common\query\GroupQuery;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\Profile;
use app\modules\main\models\ProfileGroups;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $active
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
            [['active'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('main', 'GROUP_ID'),
            'title' => Module::t('main', 'GROUP_TITLE'),
            'active' => Module::t('main', 'GROUP_ACTIVE'),
        ];
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
    public function changeActivity(){
        $this->active = !$this->active;
        return $this->save(false);
    }
    
    /**
     * Get Group Activity names array
     * 
     * @return array
     */
    public static function getActivityArray()
    {
        return [
            self::STATUS_DISABLED => Module::t('main', 'GROUP_ACTIVITY_DISABLED'),
            self::STATUS_ACTIVE => Module::t('main', 'GROUP_ACTIVITY_ACTIVE'),
        ];
    }
    
    /**
     * Get Group activity name
     * 
     * @return string
     */
    public function getActivityName()
    {
        return ArrayHelper::getValue(self::getActivityArray(), $this->active);
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
     * Get Profiles Name and Phone as string
     * 
     * @return array profiles data
     */
    public function getProfilesAsStringArray()
    {
        $result = [];
        foreach (ArrayHelper::map($this->profiles, 'name', 'phone') as $key => $value)
            $result[] = $key . ' (' . $value . ')';
        return $result;
    }
}
