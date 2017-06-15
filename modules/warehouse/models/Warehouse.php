<?php

namespace app\modules\warehouse\models;

use app\modules\warehouse\Module;
use app\modules\warehouse\models\query\WarehouseQuery;
use yii\helpers\ArrayHelper;
use app\modules\group\models\Group;
use app\components\behaviors\ManyHasManyBehavior;
use app\modules\product\models\Product;
use app\modules\product\models\Price;
use app\modules\group\models\GroupWarehouses;

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
    
    //Scenarios
    const SCENARIO_ADMIN_EDIT = 'admin_edit';
    const SCENARIO_EDITOR_EDIT = 'editor_edit';
    
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
            [['groupsList', 'productsList'], 'safe'],
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
     * Get Groups
     * 
     * @return array Groups
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(GroupWarehouses::tableName(), ['warehouse_id' => 'id']);
    }
    
    /**
     * Get Products
     * 
     * @return array Products[]
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable(Price::tableName(), ['warehouse_id' => 'id']);
    }

    /**
     * Get only active Groups
     * 
     * @return array Groups
     */
    public function getActiveGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(GroupWarehouses::tableName(), ['warehouse_id' => 'id'])
            ->where(['status' => Group::STATUS_ACTIVE]);
    }
    
    /**
     * Get only active Products
     * 
     * @return array Products
     */
    public function getActiveProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
                ->viaTable(Price::tableName(), ['warehouse_id' => 'id'])
                ->where(['status' => Product::STATUS_ACTIVE]);
    }
    
    /**
     * Get only active Groups string
     * 
     * @return array Groups
     */
    public function getActiveGroupsTitles()
    {
        $result = [];
        foreach ($this->activeGroups as $group) {
            $result[$group->id] = $group->title;
        }
        
        return $result;
    }
    
    /**
     * Get active Products titles
     * 
     * @return array Products[]
     */
    public function getActiveProductsTitles()
    {
        $result = [];
        foreach ($this->activeProducts as $product) {
            $result[$product->id] = $product->title . ($product->subtitle ? ' (' . $product->subtitle . ')' : '');
        }
        
        return $result;
    }
    
    /**
     * Get Groups titles as string
     * 
     * @return array Groups data
     */
    public function preparedForSIWActiveGroups()
    {
        $result = [];
        foreach ($this->activeGroups as $item) {
            $result[$item->id] = ['content' => $item->title];
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
        $all = static::find()
                ->select(['id', 'title'])
                ->where(['status' => self::STATUS_ACTIVE])
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
        foreach (static::find()->all() as $warehouse){
            $result[$warehouse->id] = $warehouse->title;
        }
        return $result;
    }    
}
