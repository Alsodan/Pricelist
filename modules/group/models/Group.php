<?php

namespace app\modules\group\models;

use app\modules\group\Module;
use app\modules\group\models\query\GroupQuery;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\Profile;
use app\modules\group\models\ProfileGroups;
use app\modules\user\models\common\User;
use app\components\behaviors\ManyHasManyBehavior;
use app\modules\warehouse\models\Warehouse;
use app\modules\group\models\WarehouseGroups;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 */
class Group extends \yii\db\ActiveRecord
{
    //Group Status
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
            [['profilesList', 'warehousesList'], 'safe'],
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
            [
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'warehouses' => 'warehousesList',                   
                ],
            ],            
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->profilesList = $this->profilesList;
            $this->warehousesList = $this->warehousesList;
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
     * Block Group
     * @return boolean
     */
    public function block(){
        $this->status = static::STATUS_DISABLED;
        return $this->save(false);
    }

    /**
     * Unblock Group
     * @return boolean
     */
    public function unblock(){
        $this->status = static::STATUS_ACTIVE;
        return $this->save(false);
    }

    /**
     * Get Group status names array
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
     * Get Warehouses
     * 
     * @return array warehouses
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
            ->viaTable(WarehouseGroups::tableName(), ['group_id' => 'id']);
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
     * Get only active Warehouses
     * 
     * @return array warehouses
     */
    public function getActiveWarehouses()
    {
        $result = [];
        foreach ($this->warehouses as $warehouse) {
            if ($warehouse->status == Warehouse::STATUS_ACTIVE) {
                $result[] = $warehouse;
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
     * Get only active Warehouses string
     * 
     * @return array warehouses titles
     */
    public function getWarehousesAsStringArray()
    {
        $result = [];
        foreach ($this->activeWarehouses as $warehouse) {
            $result[$warehouse->id] = $warehouse->title;
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
     * Get Warehouse title as string
     * 
     * @return array profiles data
     */
    public function preparedForSIWActiveWarehouses()
    {
        $result = [];
        foreach ($this->activeWarehouses as $item) {
            $result[$item->id] = ['content' => $item->title];
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
