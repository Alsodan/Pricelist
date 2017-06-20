<?php

namespace app\modules\user\models\common;

use Yii;
use yii\db\ActiveRecord;
use app\modules\user\models\common\User;
use app\modules\user\models\common\query\ProfileQuery;
use app\modules\user\Module;
use app\modules\group\models\Group;
use app\modules\group\models\ProfileGroups;
use app\modules\warehouse\models\Warehouse;
use app\modules\product\models\Product;
use app\modules\product\models\ProfileProducts;
use app\modules\warehouse\models\WarehouseProfiles;
use \app\components\behaviors\ManyHasManyBehavior;

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
            [['groupsList', 'user_id'], 'safe'],
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'groups' => 'groupsList',                   
                ],
            ],
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->groupsList)) {
                $this->groupsList = is_array($this->groupsList) ? $this->groupsList : [$this->groupsList];
            }
            return true;
        } else {
            return false;
        }
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
     * Get only active Groups
     * 
     * @return array Groups[]
     */
    public function getActiveGroups()
    {
        $result = [];
        foreach ($this->groups as $group) {
            if ($group->status == Group::STATUS_ACTIVE) {
                $result[] = $group;
            }
        }
        
        return $result;
    }
    
    /**
     * Get only active Products
     * 
     * @return array Products[]
     */
    public function getActiveProducts()
    {
        $result = [];
        foreach ($this->products as $product) {
            if ($product->status == Product::STATUS_ACTIVE) {
                $result[] = $product;
            }
        }
        
        return $result;
    }
    
    /**
     * Get Group Titles array
     */
    public function getGroupsTitleArray()
    {
        $result = [];
        foreach ($this->activeGroups as $group){
            $result[$group->id] = $group->title;
        }
        return $result;
    }
    
    /**
     * Get Products Titles array
     */
    public function getProductsTitleArray()
    {
        $result = [];
        foreach ($this->activeProducts as $item){
            $result[$item->id] = $item->title . ($item->subtitle ? ' (' . $item->subtitle . ')' : '');
        }
        return $result;
    }  
}
