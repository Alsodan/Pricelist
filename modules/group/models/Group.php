<?php

namespace app\modules\group\models;

use app\modules\group\Module;
use app\modules\group\models\query\GroupQuery;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\User;
use app\components\behaviors\ManyHasManyBehavior;
use app\modules\warehouse\models\Warehouse;
use app\modules\product\models\Product;
use app\modules\group\models\GroupUsers;
use app\modules\group\models\GroupWarehouses;

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
    
    //Scenarios
    const SCENARIO_ADMIN_EDIT = 'admin_edit';
    const SCENARIO_EDITOR_EDIT = 'editor_edit';
    
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
            ['title', 'required'],
            [['usersList', 'warehousesList', 'productsList'], 'safe'],
        ];
    }
    
    /**
     * Scenarios
     * @return string
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_EDIT] = ['status', 'title'];
        $scenarios[self::SCENARIO_EDITOR_EDIT] = ['title'];
        return $scenarios;
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
                    'users' => 'usersList',
                ],
            ],
            [
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'warehouses' => 'warehousesList',
                ],
            ],
            /*[
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'products' => 'productsList',
                ],
            ],*/
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->usersList = $this->usersList;
            $this->warehousesList = $this->warehousesList;
            //$this->productsList = $this->productsList;
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
     * Get Users
     * 
     * @return array User[]
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                ->viaTable(GroupUsers::tableName(), ['group_id' => 'id']);
    }
    
    /**
     * Get Warehouses
     * 
     * @return array warehouses
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
            ->viaTable(GroupWarehouses::tableName(), ['group_id' => 'id']);
    }
    
    /**
     * Get Products
     * 
     * @return array Products[]
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable(ProductGroups::tableName(), ['group_id' => 'id']);
    }
    
    /**
     * Get only active Users
     * 
     * @return array User[]
     */
    public function getActiveUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                    ->viaTable(GroupUsers::tableName(), ['group_id' => 'id'])
                    ->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    /**
     * Get only active Warehouses
     * 
     * @return array warehouses
     */
    public function getActiveWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
                    ->viaTable(GroupWarehouses::tableName(), ['group_id' => 'id'])
                    ->andWhere(['status' => User::STATUS_ACTIVE]);
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
     * Get only active Users names
     * 
     * @return array names[]
     */
    public function getActiveUsersNames()
    {
        $result = [];
        foreach ($this->activeUsers as $item) {
            $result[$item->id] = $item->profile->name . ' (' . $item->profile->phone . ')';
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
     * Get only active Products string
     * 
     * @return array Products titles
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
    /*public function preparedForSIWActiveProfiles()
    {
        $result = [];
        foreach ($this->activeProfiles as $profile) {
            $result[$profile->id] = ['content' => $profile->name . ' (' . $profile->phone . ')'];
        }
        
        return $result;
    }*/

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
     * Get Products title as string
     * 
     * @return array Products data
     */
    public function preparedForSIWActiveProducts()
    {
        $result = [];
        foreach ($this->activeProducts as $item) {
            $result[$item->id] = ['content' => $item->title . ($item->subtitle ? ' (' . $item->subtitle . ')' : '')];
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
