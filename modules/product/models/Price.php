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
use app\modules\product\models\ProductGroups;
use app\modules\product\models\PriceUsers;

/**
 * This is the model class for table "{{%price}}".
 *
 * @property integer $id
 * @property double $price_no_tax
 * @property double $price_with_tax
 * @property integer $warehouse_id
 * @property integer $product_id
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'product_id'], 'integer'],
            [['price_no_tax', 'price_with_tax'], 'double'],
            [['warehouse_id', 'product_id'], 'required'],
            [['call_no_tax', 'call_with_tax'], 'safe'],
            ['usersList', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('product', 'PRODUCT_ID'),
            'warehouse_id' => Module::t('product', 'WAREHOUSE_ID'),
            'product_id' => Module::t('product', 'PRODUCT_ID'),
            'call_no_tax' => Module::t('product', 'CALL_FOR_PRICE'),
            'call_with_tax' => Module::t('product', 'CALL_FOR_PRICE'),
            'price_no_tax' => Module::t('product', 'PRODUCT_PRICE_NO_TAX'),
            'price_with_tax' => Module::t('product', 'PRODUCT_PRICE_WITH_TAX'),
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
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->usersList = $this->usersList;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    /*public static function find()
    {
        return new PriceQuery(get_called_class());
    }*/
    
    /**
     * Get Users
     * 
     * @return array User[]
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable(PriceUsers::tableName(), ['price_id' => 'id']);
    }
    
    /**
     * Get Price Warehouse
     * 
     * @return app/modules/warehouse/models/Warehouse
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    /**
     * Get Price Product
     * 
     * @return app/modules/product/models/Product
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    
    /**
     * Get only active Users
     * 
     * @return array app/modules/user/models/common/User[]
     */
    public function getActiveUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                ->viaTable(PriceUsers::tableName(), ['price_id' => 'id'])
                ->andWhere(['status' => User::STATUS_ACTIVE]);
    }
    
    /**
     * Get active Users names
     * 
     * @return array
     */
    public function getActiveUsersNames()
    {
        $result = [];
        foreach ($this->activeUsers as $item) {
            $result[$item->profile->id] = $item->profile->name . ' (' . $item->profile->phone . ')';
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
        foreach ($this->activeWarehouses as $item) {
            $result[$item->id] = $item->title;
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
        foreach ($this->activeUsers as $item) {
            $result[$item->profile->id] = ['content' => $item->profile->name . ' (' . $item->profile->phone . ')'];
        }
        
        return $result;
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
