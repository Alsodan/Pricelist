<?php

namespace app\modules\warehouse\models;

use app\modules\warehouse\Module;
use app\modules\warehouse\models\query\WarehouseQuery;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\Profile;
use app\modules\warehouse\models\WarehouseProfiles;
use app\modules\group\models\WarehouseGroups;
use app\modules\user\models\common\User;
use app\modules\group\models\Group;
use app\components\behaviors\ManyHasManyBehavior;
use app\modules\product\models\Product;
use app\modules\product\models\WarehouseProducts;

/**
 * This is the model class for table "{{%warehouse}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 */
class Warehouse extends \yii\db\ActiveRecord
{
    //Warehouse status
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['profilesList', 'groupsList', 'productsList'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('warehouse', 'WAREHOUSE_ID'),
            'title' => Module::t('warehouse', 'WAREHOUSE_TITLE'),
            'status' => Module::t('warehouse', 'WAREHOUSE_STATUS'),
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
                    'groups' => 'groupsList',                   
                ],
            ],
            [
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'products' => 'productsList',                   
                ],
            ],
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->profilesList = $this->profilesList;
            $this->groupsList = $this->groupsList;
            $this->productsList = $this->productsList;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     * @return WarehouseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WarehouseQuery(get_called_class());
    }
    
    /**
     * Block Warehouse
     * @return boolean
     */
    public function block(){
        $this->status = static::STATUS_DISABLED;
        return $this->save(false);
    }
    
    /**
     * Unblock Warehouse
     * @return boolean
     */
    public function unblock(){
        $this->status = static::STATUS_ACTIVE;
        return $this->save(false);
    }    
   
    /**
     * Get Warehouse Status names array
     * 
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            static::STATUS_DISABLED => Module::t('warehouse', 'WAREHOUSE_STATUS_DISABLED'),
            static::STATUS_ACTIVE => Module::t('warehouse', 'WAREHOUSE_STATUS_ACTIVE'),
        ];
    }
    
    /**
     * Get Group status name
     * 
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(static::getStatusArray(), $this->status);
    }
    
    /**
     * Get Profiles
     * 
     * @return array profiles
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['id' => 'profile_id'])
            ->viaTable(WarehouseProfiles::tableName(), ['warehouse_id' => 'id']);
    }
    
    /**
     * Get Groups
     * 
     * @return array Groups
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(WarehouseGroups::tableName(), ['warehouse_id' => 'id']);
    }
    
    /**
     * Get Products
     * 
     * @return array Products[]
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable(WarehouseProducts::tableName(), ['warehouse_id' => 'id']);
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
     * Get only active Groups
     * 
     * @return array Groups
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
     * @return array Products
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
     * Get only active Groups string
     * 
     * @return array Groups
     */
    public function getGroupsAsStringArray()
    {
        $result = [];
        foreach ($this->activeGroups as $group) {
            $result[$group->id] = $group->title;
        }
        
        return $result;
    }
    
    /**
     * Get only active Products string
     * 
     * @return array Products[]
     */
    public function getProductsAsStringArray()
    {
        $result = [];
        foreach ($this->activeProducts as $product) {
            $result[$product->id] = $product->title . ($product->subtitle ? ' (' . $product->subtitle . ')' : '');
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
     * Get Products titles as string
     * 
     * @return array Products data
     */
    public function preparedForSIWActiveProducts()
    {
        $result = [];
        foreach ($this->activeProducts as $product) {
            $result[$product->id] = ['content' => $product->title . ($product->subtitle ? ' (' . $product->subtitle . ')' : '')];
        }
        
        return $result;
    }
    
    /**
     * Get Active Warehouses list for Sorted Input widget
     */
    public static function preparedForSIWActiveWarehouses()
    {
        $all = Warehouse::find()
                ->where(['status' => Warehouse::STATUS_ACTIVE])
                ->all();

        $result = [];
        foreach ($all as $item){
            $result[$item->id] = ['content' => $item->title];
        }
        
        return $result;
    }
    
    /**
     * Get Warehouses Dropdown
     */
    public static function getWarehousesDropdown()
    {
        $result = [];
        foreach (self::find()->all() as $warehouse){
            $result[$warehouse->id] = $warehouse->title;
        }
        return $result;
    }    
}
