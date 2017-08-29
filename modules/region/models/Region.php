<?php

namespace app\modules\region\models;

use app\modules\region\Module;
use app\modules\region\models\query\RegionQuery;
use app\modules\warehouse\models\Warehouse;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 */
class Region extends \yii\db\ActiveRecord
{
    //Region status
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
        return '{{%region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['status', 'sort'], 'integer'],
            ['sort', 'default', 'value' => 0],
            [['title'], 'required'],
        ];
    }

    /**
     * Scenarios
     * @return string
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_EDIT] = ['status', 'title', 'sort'];
        $scenarios[self::SCENARIO_EDITOR_EDIT] = ['title', 'sort'];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Module::t('region', 'REGION_TITLE'),
            'status' => Module::t('region', 'REGION_STATUS'),
            'sort' => Module::t('region', 'REGION_SORT'),
        ];
    }
    
    /**
     * @inheritdoc
     * @return CropQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegionQuery(get_called_class());
    }
    
    /**
     * Block Region
     * @return boolean
     */
    public function block(){
        $this->status = static::STATUS_DISABLED;
        return $this->save(false);
    }
    
    /**
     * Unblock Region
     * @return boolean
     */
    public function unblock(){
        $this->status = static::STATUS_ACTIVE;
        return $this->save(false);
    }
    
    /**
     * Get Region Status names array
     * 
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            static::STATUS_DISABLED => Module::t('region', 'STATUS_DISABLED'),
            static::STATUS_ACTIVE => Module::t('region', 'STATUS_ACTIVE'),
        ];
    }
    
    /**
     * Get Region status name
     * 
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(static::getStatusArray(), $this->status);
    }
    
    /**
     * Get Warehouse
     * 
     * @return Warehouse
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['region_id' => 'id']);
    }
    
    /**
     * Get active Warehouse
     * 
     * @return Warehouse
     */
    public function getActiveWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['region_id' => 'id'])
                ->where(['status' => Warehouse::STATUS_ACTIVE]);
    }
    
    /**
     * Get Warehouses names
     * 
     * @return string
     */
    public function getWarehousesNames()
    {
        $result = [];
        foreach ($this->warehouses as $warehouse) {
            $result[] = $warehouse->title;
        }
        return implode('<br>', $result);
    }
    
    /**
     * Get Warehouses titles as string
     * 
     * @return array Warehouses data
     */
    public function preparedForSIWActiveWarehouses()
    {
        $result = [];
        foreach ($this->activeWarehouses as $item) {
            $result[$item->id] = ['content' => $item->title];
        }
        
        return $result;
    }
}
