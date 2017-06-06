<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\Module;
use app\modules\product\models\query\ProductQuery;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\Profile;
use app\modules\warehouse\models\Warehouse;
use app\modules\group\models\Group;
use app\modules\crop\models\Crop;
use app\modules\product\models\ProfileProducts;
use app\modules\product\models\WarehouseProducts;
use app\modules\user\models\common\User;
use app\components\behaviors\ManyHasManyBehavior;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $crop_id
 * @property integer $grade
 * @property string $title
 * @property string $subtitle
 * @property string $specification
 * @property double $price_no_tax
 * @property double $price_with_tax
 * @property integer $status
 */
class Product extends \yii\db\ActiveRecord
{
    //Product status
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    
    public $call_with_tax;
    public $call_no_tax;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'group_id', 'crop_id', 'grade'], 'integer'],
            [['price_no_tax', 'price_with_tax'], 'double'],
            [['title', 'subtitle'], 'string', 'max' => 100],
            [['title', 'group_id', 'crop_id'], 'required'],
            [['specification', 'call_no_tax', 'call_with_tax'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('product', 'PRODUCT_ID'),
            'group_id' => Module::t('product', 'GROUP'),
            'crop_id' => Module::t('product', 'CROP'),
            'grade' => Module::t('product', 'PRODUCT_GRADE'),
            'title' => Module::t('product', 'PRODUCT_TITLE'),
            'subtitle' => Module::t('product', 'PRODUCT_SUBTITLE'),
            'specification' => Module::t('product', 'PRODUCT_SPECIFICATION'),
            'price_no_tax' => Module::t('product', 'PRODUCT_PRICE_NO_TAX'),
            'price_with_tax' => Module::t('product', 'PRODUCT_PRICE_WITH_TAX'),
            'status' => Module::t('product', 'PRODUCT_STATUS'),
            'call_no_tax' => Module::t('product', 'CALL_FOR_PRICE'),
            'call_with_tax' => Module::t('product', 'CALL_FOR_PRICE'),
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
            if ($this->call_with_tax == 1) {
                $this->price_with_tax = -1;
            }
            if ($this->call_no_tax == 1) {
                $this->price_no_tax = -1;
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
    
    /**
     * Block Product
     * @return boolean
     */
    public function block(){
        $this->status = static::STATUS_DISABLED;
        return $this->save(false);
    }
    
    /**
     * Unblock Product
     * @return boolean
     */
    public function unblock(){
        $this->status = static::STATUS_ACTIVE;
        return $this->save(false);
    }    
   
    /**
     * Get Product Status names array
     * 
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            static::STATUS_DISABLED => Module::t('product', 'PRODUCT_STATUS_DISABLED'),
            static::STATUS_ACTIVE => Module::t('product', 'PRODUCT_STATUS_ACTIVE'),
        ];
    }
    
    /**
     * Get Product status name
     * 
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(static::getStatusArray(), $this->status);
    }
    
    /**
     * Get Product Group
     * 
     * @return app/modules/group/models/Group
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }
       
    /**
     * Get Product Crop
     * 
     * @return app/modules/crop/models/Crop
     */
    public function getCrop()
    {
        return $this->hasOne(Crop::className(), ['id' => 'crop_id']);
    }

    /**
     * Get Profiles
     * 
     * @return array profiles
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['id' => 'profile_id'])
            ->viaTable(ProfileProducts::tableName(), ['product_id' => 'id']);
    }
    
    /**
     * Get Warehouses
     * 
     * @return array Warehouses[]
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
            ->viaTable(WarehouseProducts::tableName(), ['product_id' => 'id']);
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
     * @return array Warehouses[]
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
     * @return array Warehouses[]
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
     * Get Warehouses titles as string
     * 
     * @return array warehouses data
     */
    public function preparedForSIWActiveWarehouses()
    {
        $result = [];
        foreach ($this->activeWarehouses as $warehouse) {
            $result[$warehouse->id] = ['content' => $warehouse->title];
        }
        
        return $result;
    }
    
    /**
     * Get Active Products list for Sorted Input widget
     */
    public static function preparedForSIWActiveProducts()
    {
        $all = static::find()
                ->where(['status' => static::STATUS_ACTIVE])
                ->all();

        $result = [];
        foreach ($all as $item){
            $result[$item->id] = ['content' => $item->title];
        }
        
        return $result;
    }
    
    /**
     * Get Products Dropdown
     */
    public static function getDropdown()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'title');
    }
    
    /**
     * Decorate price before output
     * @param type $priceType
     * @return type
     */
    public function getPrice($priceType = 'price_with_tax')
    {
        if ($this->canGetProperty($priceType)) {
            return $this->$priceType < 0 ? Module::t('product', 'CALL_FOR_PRICE') : ($this->$priceType > 0 ? Yii::$app->formatter->asCurrency($this->$priceType) : Module::t('product', 'NOT_BUY'));
        }
        
        return Module::t('product', 'PRICE_NOT_SET');
    }
    
    /**
     * Prepare Product to show
     * @return $this
     */
    public function prepared()
    {
        if ($this->price_with_tax == -1) {
            $this->call_with_tax = 1;
        }
        if ($this->price_no_tax == -1) {
            $this->call_no_tax = 1;
        }
        
        return $this;
    }
}
