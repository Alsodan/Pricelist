<?php

namespace app\modules\crop\models;

use app\modules\crop\Module;
use app\modules\crop\models\query\CropQuery;
use yii\helpers\ArrayHelper;
use app\modules\product\models\Product;

/**
 * This is the model class for table "{{%crop}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $sort
 */
class Crop extends \yii\db\ActiveRecord
{
    //Warehouse status
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%crop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            ['sort', 'integer'],
            ['sort', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('crop', 'CROP_ID'),
            'title' => Module::t('crop', 'CROP_TITLE'),
            'sort' => Module::t('crop', 'CROP_SORT'),
        ];
    }
    
    /**
     * @inheritdoc
     * @return CropQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CropQuery(get_called_class());
    }
    
    /**
     * Block Crop
     * @return boolean
     */
    public function block(){
        $this->status = static::STATUS_DISABLED;
        return $this->save(false);
    }
    
    /**
     * Unblock Crop
     * @return boolean
     */
    public function unblock(){
        $this->status = static::STATUS_ACTIVE;
        return $this->save(false);
    }
    
    /**
     * Get Products
     * 
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['crop_id' => 'id']);
    }
    
    /**
     * Get Crops Dropdown
     */
    public static function getCropsDropdown()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'title');
    }    
}
