<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\Module;
use app\modules\product\models\query\ProductQuery;
use yii\helpers\ArrayHelper;
use app\modules\warehouse\models\Warehouse;
use app\modules\user\models\common\User;
use app\components\behaviors\ManyHasManyBehavior;
use app\modules\product\models\PriceUsers;

/**
 * This is the model class for table "{{%price}}".
 *
 * @property integer $id
 * @property double $price_no_tax
 * @property double $price_with_tax
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property integer $price_status
 */

class Price extends \yii\db\ActiveRecord
{
    //Price statuses
    const CALL_WITH_TAX = 1 << 0;
    const CALL_NO_TAX = 1 << 1;
    const NONEED_WITH_TAX = 1 << 2;
    const NONEED_NO_TAX = 1 << 3;
    
    public $call_with_tax;
    public $call_no_tax;
    public $noneed_with_tax;
    public $noneed_no_tax;
    
    //Product prices
    const PRICE_NO_TAX = 'price_no_tax';
    const PRICE_WITH_TAX = 'price_with_tax';
    
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
            //[['call_with_tax', 'call_no_tax', 'noneed_with_tax', 'noneed_no_tax'], 'safe'],
            //['price_status', 'safe'],
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
            'price_no_tax' => Module::t('product', 'PRODUCT_PRICE_NO_TAX'),
            'price_with_tax' => Module::t('product', 'PRODUCT_PRICE_WITH_TAX'),
            'price_status' => Module::t('product', 'PRODUCT_PRICE_STATUS'),
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
                'class' => \app\components\behaviors\LogChangesBehavior::className(),
                //'fields' => ['price_no_tax'],
                'eventsOnly' => [
                    \yii\db\ActiveRecord::EVENT_AFTER_INSERT,
                    \yii\db\ActiveRecord::EVENT_AFTER_UPDATE
                ],
                'objectTitle' => 'Цена',
                //'objectType' => 'price',
                'fieldName' => 'Цена',
                'registerUserId' => 0
            ]
        ];
    }
    
    //Before save model
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->usersList = $this->usersList;
            
            //Calculate status
            $this->price_status = 0;
            if ($this->call_with_tax) {
                $this->price_status |= self::CALL_WITH_TAX;
            }
            if ($this->call_no_tax) {
                $this->price_status |= self::CALL_NO_TAX;
            }
            if ($this->noneed_with_tax) {
                $this->price_status |= self::NONEED_WITH_TAX;
            }
            if ($this->noneed_no_tax) {
                $this->price_status |= self::NONEED_NO_TAX;
            }
            return true;
        } else {
            return false;
        }
    }
    
    //After finding and populating model
    public function afterFind() {
        parent::afterFind();
        
        //Set flags from $price_status
        if (is_null($this->price_status)) {
                $this->price_status = self::NONEED_WITH_TAX | self::NONEED_NO_TAX;
        }
        $this->call_with_tax = $this->price_status & self::CALL_WITH_TAX;
        $this->call_no_tax = $this->price_status & self::CALL_NO_TAX;
        $this->noneed_with_tax = $this->price_status & self::NONEED_WITH_TAX;
        $this->noneed_no_tax = $this->price_status & self::NONEED_NO_TAX;
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
     * Get Price active Warehouse
     * 
     * @return app/modules/warehouse/models/Warehouse
     */
    public function getActiveWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id'])
                ->where(['status' => Warehouse::STATUS_ACTIVE]);
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
            $result[$item->profile->id] = $item->profile->name/* . '<br>(' . $item->profile->phone . ')'*/;
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
     * 
     * @param type $priceType - can be 'price_with_tax' or simple 'with_tax'
     * @return type
     */
    public function getPrice($priceType = 'with_tax')
    {
        if (substr($priceType, 0, 6) == 'price_') {
            $priceType = substr($priceType, 6);
        }
        $price =  'price_' . $priceType;
        if ($this->canGetProperty($price)) {
            $call = 'call_' . $priceType;
            $noneed = 'noneed_' . $priceType;
            return $this->$call > 0 ? Module::t('product', 'CALL_FOR_PRICE') :
                    ($this->$noneed > 0 ? Module::t('product', 'NOT_BUY') : Yii::$app->formatter->asCurrency((double)$this->$price));
        }
        
        return Module::t('product', 'PRICE_NOT_SET');
    }
    
    public static function getPriceText($data, $isKey)
    {
        if ($isKey) {
            $priceCall = $data & self::CALL_NO_TAX;
            $priceNoNeed = $data & self::NONEED_NO_TAX;
            
            return $priceCall > 0 ? Module::t('product', 'CALL_FOR_PRICE') :
                    ($priceNoNeed > 0 ? Module::t('product', 'NOT_BUY') : Module::t('product', 'PRICE_NOT_SET'));
        } else {
            return Yii::$app->formatter->asCurrency($data);
        }
        if (is_null($this->price_status)) {
                $this->price_status = self::NONEED_WITH_TAX | self::NONEED_NO_TAX;
        }
    }

    /**
     * Get Product Prices names array
     * 
     * @return array
     */
    public static function getPricesArray()
    {
        return [
            static::PRICE_WITH_TAX => Module::t('product', 'PRODUCT_PRICE_WITH_TAX'),
            static::PRICE_NO_TAX => Module::t('product', 'PRODUCT_PRICE_NO_TAX'),
        ];
    }
    
    /**
     * Get Price name
     * 
     * @return string
     */
    public function getPriceName($attribute)
    {
        return ArrayHelper::getValue(static::getPricesArray(), $attribute);
    }
    
    /**
     * Get Prices string
     * @param type $delimiter
     * @return string
     */
    public function getPrices($showCaption = true, $captionDelimiter = ' - ', $itemDelimiter = '<br>')
    {
        $result = [];
        foreach (static::getPricesArray() as $key => $value) {
            $result[] = ($showCaption ? $value . $captionDelimiter : '') . $this->getPrice($key);
        }
        return implode($itemDelimiter, $result);
    }
    
    /**
     * Get Price No tax string
     * @param type $delimiter
     * @return string
     */
    public function getPricesNoTax($showCaption = true, $captionDelimiter = ' - ')
    {
        return ($showCaption ? Module::t('product', 'PRODUCT_PRICE_NO_TAX') . $captionDelimiter : '') . $this->getPrice('price_no_tax');
    }
}
