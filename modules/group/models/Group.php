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
use app\modules\group\models\GroupProducts;
use app\modules\product\models\Price;

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
            [
                'class' => ManyHasManyBehavior::className(),
                'relations' => [
                    'groupProducts' => 'productsList',
                ],
            ],
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->usersList = $this->usersList;
            $this->warehousesList = $this->warehousesList;
            $this->productsList = $this->productsList;
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
        $warehousesIDs = ArrayHelper::getColumn($this->warehouses, 'id');
        $prices = Price::findAll(['warehouse_id' => $warehousesIDs]);
        
        return Product::findAll(['id' => array_unique(ArrayHelper::getColumn($prices, 'id'))]);
    }
        
    /**
     * Get Products
     * 
     * @return array Products[]
     */
    public function getGroupProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable(GroupProducts::tableName(), ['group_id' => 'id']);
        /*$warehousesIDs = ArrayHelper::getColumn($this->warehouses, 'id');
        $prices = Price::findAll(['warehouse_id' => $warehousesIDs]);
        
        return Product::findAll(['id' => array_unique(ArrayHelper::getColumn($prices, 'id'))]);*/
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
        foreach ($this->activeProducts as $item) {
            $base[$item->id] = array_merge(['title' => $item->title . ($item->subtitle ? '<br>(' . $item->subtitle . ')' : '')], $columns);
        }

        $data = Price::find()
                ->where(['warehouse_id' => ArrayHelper::getColumn($this->activeWarehouses, 'id')])
                ->andWhere(['product_id' => ArrayHelper::getColumn($this->activeProducts, 'id')])
                ->all();
        
        //Заполняем массив данными
        foreach ($data as $item) {
            $base[$item->product_id][preg_replace('~[^-a-z0-9_]+~u', '', strtolower(str_replace($cyr, $lat, $item->warehouse->title)))] = $item;
        }
        
        $result['columns'] = $whColumns;
        $result['data'] = $base;
        
        return $result;
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
     * Get only active Users
     * 
     * @return array User[]
     */
    public function getActiveDirectors()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                    ->viaTable(GroupUsers::tableName(), 
                        ['group_id' => 'id'],
                        function ($query) {$query->andWhere(['rule' => 1]);})
                    ->andWhere(['status' => User::STATUS_ACTIVE])
                    ->andWhere('role != :role', ['role' => 'roleUser']);
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
                    ->andWhere(['status' => Warehouse::STATUS_ACTIVE])
                    ->orderBy([
                        Warehouse::tableName() . '.sort' => SORT_ASC,
                        Warehouse::tableName() . '.title' => SORT_ASC,
                    ]);
    }

    /**
     * Get only active Products
     * 
     * @return array Products[]
     */
    public function getActiveProducts($warehousesIDs = [])
    {
        /*return $this->hasMany(Product::className(), ['id' => 'product_id'])
                    ->viaTable(GroupProducts::tableName(), ['group_id' => 'id'])
                    ->andWhere(['status' => Product::STATUS_ACTIVE]);*/
        if (empty($warehousesIDs)) {
            $warehousesIDs = ArrayHelper::getColumn($this->activeWarehouses, 'id');
        }
        //$prices = Price::findAll(['warehouse_id' => $warehousesIDs]);

        /*$data = Product::find()
                ->where(['id' => array_unique(ArrayHelper::getColumn($prices, 'product_id'))])
                ->active()
                ->all();*/
        
        /*$xxx = $this->hasMany(Product::className(), ['id' => 'product_id'])
                    ->viaTable(GroupProducts::tableName(), ['group_id' => 'id'])
                    ->joinWith('prices')
                    ->where([Price::tableName() . '.warehouse_id' => $warehousesIDs])
                    ->andWhere(['status' => Product::STATUS_ACTIVE]);
        echo $xxx->createCommand()->rawSql;die();*/
        
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
                    ->viaTable(GroupProducts::tableName(), ['group_id' => 'id'])
                    ->joinWith('prices')
                    ->where([Price::tableName() . '.warehouse_id' => $warehousesIDs])
                    ->andWhere(['status' => Product::STATUS_ACTIVE])
                    ->orderBy([
                        Product::tableName() . '.sort' => SORT_ASC,
                        Product::tableName() . '.title' => SORT_ASC,
                    ])
                    ->all();
    }
    
    public function getActiveGroupProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
                    ->viaTable(GroupProducts::tableName(), ['group_id' => 'id'])
                    ->andWhere(['status' => Product::STATUS_ACTIVE]);
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
            $result[$item->id] = $item->profile->name/* . ' (' . $item->profile->phone . ')'*/;
        }
        
        return $result;
    }
    
    /**
     * Get only active directors names
     * 
     * @return array names[]
     */
    public function getActiveDirectorsNames()
    {
        $result = [];
        foreach ($this->activeDirectors as $item) {
            $result[$item->id] = $item->profile->name/* . ' (' . $item->profile->phone . ')'*/;
        }
        
        return $result;
    }
    
    /**
     * Get active Warehouses titles
     * 
     * @return array warehouses titles
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
     * Get active Products titles
     * 
     * @return array Products titles
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
     * Get active Products titles
     * 
     * @return array Products titles
     */
    public function getActiveGroupProductsTitles()
    {
        $result = [];
        foreach ($this->activeGroupProducts as $product) {
            $result[$product->id] = $product->title . ($product->subtitle ? ' (' . $product->subtitle . ')' : '');
        }
        
        return $result;
    }  
    
    /**
     * Get User Name and Phone
     * 
     * @return array
     */
    public function preparedForSIWActiveUsers()
    {
        $result = [];
        foreach ($this->activeUsers as $item) {
            $result[$item->profile->id] = ['content' => $item->profile->name . ' (' . $item->profile->phone . ')'];
        }
        
        return $result;
    }

    /**
     * Get active Director Name and Phone
     * 
     * @return array
     */
    public function preparedForSIWActiveDirectors()
    {
        $result = [];
        foreach ($this->activeDirectors as $item) {
            $result[$item->profile->id] = ['content' => $item->profile->name . ' (' . $item->profile->phone . ')'];
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
     * Get Products title as string
     * 
     * @return array Products data
     */
    public function preparedForSIWActiveProducts($warehouses = [])
    {
        $result = [];
        foreach ($this->getActiveProducts($warehouses) as $item) {
            $result[$item->id] = ['content' => $item->title . ($item->subtitle ? ' (' . $item->subtitle . ')' : '')];
        }
        
        return $result;
    }
    
    /**
     * Get Products title as string
     * 
     * @return array Products data
     */
    public function preparedForSIWActiveGroupProducts()
    {
        $result = [];
        foreach ($this->activeGroupProducts as $item) {
            $result[$item->id] = ['content' => $item->title . ($item->subtitle ? ' (' . $item->subtitle . ')' : '')];
        }
        
        return $result;
    }
    
    /**
     * Get Active Groups list for Sorted Input widget
     */
    public static function preparedForSIWActiveGroups()
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
    
    /**
     * Проставляем в табличку GroupUsers в поле rule 1, если пользователь управляет группой
     * 0 - если не управляет
     * @param array $directorsIdsNew - массив новых ИД пользователей, которые управляют группой
     */
    public function resetDirectors($directorsIdsNew)
    {
        //Массив ИД текущих руководителей группы
        $directorsIdsOld = array_column(GroupUsers::find()->select('user_id')->where(['group_id' => $this->id])->andWhere(['rule' => 1])->asArray()->all(), 'user_id');
        
        if (count($directorsIdsOld) > count($directorsIdsNew)) {
            //Находим запись о том, кого нужно убрать из руководителей
            $directorToRemove = GroupUsers::findOne(['group_id' => $this->id, 'user_id' => current(array_diff($directorsIdsOld, $directorsIdsNew))]);
            $directorToRemove->updateAttributes(['rule' => 0]);
        } else {
            //Находим того, кого надо добавить
            $newDirector = GroupUsers::findOne(['group_id' => $this->id, 'user_id' => current(array_diff($directorsIdsNew, $directorsIdsOld))]);
            $newDirector->updateAttributes(['rule' => 1]);
        }
    }
}
