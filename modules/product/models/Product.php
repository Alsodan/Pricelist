<?php

namespace app\modules\product\models;

use app\modules\product\Module;
use app\modules\product\models\query\ProductQuery;
use yii\helpers\ArrayHelper;
use app\modules\warehouse\models\Warehouse;
use app\modules\crop\models\Crop;
use app\modules\user\models\common\User;
use app\components\behaviors\ManyHasManyBehavior;
use app\modules\product\models\Price;
use app\modules\group\models\Group;
use app\modules\group\models\GroupProducts;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $crop_id
 * @property integer $grade
 * @property string $title
 * @property string $subtitle
 * @property string $specification
 * @property integer $status
 * @property integer $sort
 */
class Product extends \yii\db\ActiveRecord
{
    //Product status
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
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'crop_id', 'grade', 'sort'], 'integer'],
            [['title', 'subtitle'], 'string', 'max' => 100],
            [['title', 'crop_id'], 'required'],
            ['specification', 'safe'],
            ['warehousesList', 'safe'],
            ['sort', 'default', 'value' => 0],
        ];
    }

    /**
     * Scenarios
     * @return string
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_EDIT] = ['status', 'crop_id', 'grade', 'title', 'subtitle', 'specification', 'sort'];
        $scenarios[self::SCENARIO_EDITOR_EDIT] = ['crop_id', 'grade', 'title', 'subtitle', 'specification', 'sort'];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('product', 'PRODUCT_ID'),
            'crop_id' => Module::t('product', 'CROP'),
            'grade' => Module::t('product', 'PRODUCT_GRADE'),
            'title' => Module::t('product', 'PRODUCT_TITLE'),
            'subtitle' => Module::t('product', 'PRODUCT_SUBTITLE'),
            'specification' => Module::t('product', 'PRODUCT_SPECIFICATION'),
            'status' => Module::t('product', 'PRODUCT_STATUS'),
            'sort' => Module::t('product', 'PRODUCT_SORT'),
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
                    'warehouses' => 'warehousesList',                   
                ],
            ],
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->warehousesList = $this->warehousesList;

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
        $this->status = self::STATUS_DISABLED;
        return $this->save(false);
    }
    
    /**
     * Unblock Product
     * @return boolean
     */
    public function unblock(){
        $this->status = self::STATUS_ACTIVE;
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
     * Get Product title with subtitle
     * @return string
     */
    public function getFullTitle()
    {
        return $this->title . ($this->subtitle ? ' (' . $this->subtitle . ')' : '');
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
     * Get Product Group
     * 
     * @return app/modules/group/models/Group
     */
    public function getGroups()
    {
        /*$data = $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
            ->viaTable(Price::tableName(), ['product_id' => 'id'])
            ->with('groups')
            ->all();
        
        $result = [];
        foreach ($data as $value) {
            foreach ($value->groups as $item) {
                $result[$item->id] = $item;
            }
        }
        
        return $result;*/
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(GroupProducts::tableName(), ['product_id' => 'id']);
    }
    
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['product_id' => 'id']);
    }

    /**
     * Get Users
     * 
     * @return array User[]
     */
    public function getUsers()
    {
        $data = $this->hasMany(Price::className(), ['product_id' => 'id'])
            ->with('users.profile')
            ->all();
        
        $result = [];
        foreach ($data as $value) {
            foreach ($value->users as $item) {
                $result[$item->id] = $item;
            }
        }
        
        return $result;
    }
    
    /**
     * Get Warehouses
     * 
     * @return array Warehouses[]
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
            ->viaTable(Price::tableName(), ['product_id' => 'id']);
    }

    /**
     * Get active Product Group
     * 
     * @return app/modules/group/models/Group
     */
    public function getActiveGroups()
    {
        /*$data = $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
            ->viaTable(Price::tableName(), ['product_id' => 'id'])
            ->with('activeGroups')
            ->all();
        
        $result = [];
        foreach ($data as $value) {
            foreach ($value->activeGroups as $item) {
                $result[$item->id] = $item;
            }
        }
        
        return $result;*/
        
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(GroupProducts::tableName(), ['product_id' => 'id'])
            ->where([Group::tableName() . '.status' => Group::STATUS_ACTIVE]);
    }
    
    /**
     * Get active Product Users
     * 
     * @return app/modules/group/models/Group
     */
    public function getActiveUsers()
    {
        $result = [];
        foreach ($this->users as $item) {
            if ($item->status == User::STATUS_ACTIVE) {
                $result[$item->id] = $item;
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
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
                ->viaTable(Price::tableName(), ['product_id' => 'id'])
                ->andWhere(['status' => Warehouse::STATUS_ACTIVE]);
    }
    
    /**
     * Get only active Profiles string
     * 
     * @return array profiles
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
     * Query for linked data: price, warehouse and managers
     * @return ActiveQuery
     */
    public function getLinkedData()
    {
        return Price::find()
            ->where(['product_id' => $this->id])
            ->with('activeWarehouse')
            ->with('users.profile')
            ->all();
    }

    /**
     * Current Product Price, Warehouse and Managers as string
     * @return string[]
     */
    public function getLinkedDataList()
    {
        $result = [];
        foreach ($this->linkedData as $price) {
            $item = $price->activeWarehouse->title . ' -> ';
            $users = [];
            foreach ($price->users as $user) {
                $users[] = $user->profileData;
            }
            $item .= (empty($users) ? Module::t('product', 'NO_MANAGERS') : implode(', ', $users)) . ' -> ' . $price->getPrice('price_no_tax') . ' / ' . $price->getPrice('price_with_tax');
            $result[] = $item;
        }
        
        return $result;
    }
    
    /**
     * Current Product Price, Warehouse and Managers as array
     * @return string[]
     */
    public function getLinkedDataArrayList()
    {
        $result = [];
        foreach ($this->linkedData as $price) {
            $users = [];
            foreach ($price->users as $user) {
                $users[] = $user->profileData;
            }
            $result[] = [
                $price->activeWarehouse->title,
                (empty($users) ? Module::t('product', 'NO_MANAGERS') : implode('<br>', $users)),
                $price->getPrice('price_no_tax') . ' / ' . $price->getPrice('price_with_tax'),
            ];
        }
        
        return $result;
    }

    /**
     * Get only active Warehouses string
     * 
     * @return array Warehouses[]
     */
    public function getActiveWarehousesTitles()
    {
        $result = [];
        foreach ($this->activeWarehouses as $warehouse) {
            $result[$warehouse->id] = $warehouse->title;
        }
        
        return $result;
    }    
    
    /**
     * Get only active Groups titles
     * 
     * @return array
     */
    public function getActiveGroupsTitles()
    {
        $result = [];
        foreach ($this->activeGroups as $item) {
            $result[$item->id] = $item->title;
        }
        
        return $result;
    }    
    
    /**
     * Data for Products Users table: products, warehouses and User[]
     * @return array
     */
    public function getUsersTable()
    {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        
        //Массив соответствия транслитерированных и обычных названий складов
        $whColumns = [];
        $columns = [];
        foreach ($this->activeWarehouses as $item) {
            $whColumns = array_merge($whColumns, [preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->title))) => ['title' => $item->title, 'id' => $item->id]]);
            $columns = array_merge($columns, [preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->title))) => '']);
        }
        
        //Расширяем массив до количества продукции
        $base = [];
        //foreach ($this->activeProducts as $item) {
            $base[$this->id] = array_merge(['title' => $this->fullTitle], $columns);
        //}

        $data = Price::find()
                ->where(['warehouse_id' => ArrayHelper::getColumn($this->activeWarehouses, 'id')])
                ->andWhere(['product_id' => $this->id])
                ->all();
        
        //Заполняем массив данными
        foreach ($data as $item) {
            $base[$this->id][preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->warehouse->title)))] = $item;
        }
        
        $result['columns'] = $whColumns;
        $result['data'] = $base;
        
        return $result;
    }
        
    /**
     * Data for Prices table: products, warehouses and Price[]
     * @return array
     */
    public function getPricesTable()
    {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        
        //Массив соответствия транслитерированных и обычных названий складов
        $whColumns = [];
        $columns = [];
        foreach ($this->activeWarehouses as $item) {
            $whColumns = array_merge($whColumns, [preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->title))) => ['title' => $item->title, 'id' => $item->id]]);
            $columns = array_merge($columns, [preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->title))) => '']);
        }
        
        //Расширяем массив до количества продукции
        $base = [];
        //foreach ($this->activeProducts as $item) {
            $base[$this->id] = array_merge(['title' => $this->fullTitle], $columns);
        //}

        $data = Price::find()
                ->where(['warehouse_id' => ArrayHelper::getColumn($this->activeWarehouses, 'id')])
                ->andWhere(['product_id' => $this->id])
                ->all();
        
        //Заполняем массив данными
        foreach ($data as $item) {
            $base[$this->id][preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->warehouse->title)))] = $item;
        }
        
        $result['columns'] = $whColumns;
        $result['data'] = $base;
        
        return $result;
    }
    
    /**
     * Generate Price table
     * @param type $products
     * @param type $warehouses
     * @return array
     */
    public static function generatePricesTable($products, $warehouses)
    {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        
        //Массив соответствия транслитерированных и обычных названий складов
        $whColumns = [];
        $columns = [];

        foreach ($warehouses as $item) {
            $whColumns = array_merge($whColumns, [preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->title))) => ['title' => $item->title, 'id' => $item->id]]);
            $columns = array_merge($columns, [preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->title))) => '']);
        }
        
        //Расширяем массив до количества продукции
        $base = [];
        foreach ($products as $item) {
            $base[$item->id] = array_merge(['title' => $item->fullTitle], $columns);
        }

        $data = Price::find()
                ->where(['warehouse_id' => ArrayHelper::getColumn($warehouses, 'id')])
                ->andWhere(['product_id' => ArrayHelper::getColumn($products, 'id')])
                ->with('product')
                ->with('warehouse')
                ->all();
        
        //Заполняем массив данными
        foreach ($data as $item) {
            $base[$item->product->id][preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->warehouse->title)))] = $item;
        }
        
        $result['columns'] = $whColumns;
        $result['data'] = $base;
        
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
    public static function preparedForSIWActiveProducts($group = [])
    {
        $query = static::find()
                ->where([Product::tableName() . '.status' => self::STATUS_ACTIVE])
                ->joinWith('groups');
        
        if (!empty($group)) {
            $query->andWhere([Group::tableName() . '.id' => $group]);
        }
        
        $all = $query->all();

        $result = [];
        foreach ($all as $item){
            $result[$item->id] = ['content' => $item->title . ($item->subtitle ? ' (' . $item->subtitle . ')' : '')];
        }
        
        return $result;
    }
}
