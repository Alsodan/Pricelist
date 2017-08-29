<?php

namespace app\modules\organization\models;

use app\modules\organization\Module;
use app\modules\organization\models\query\OrganizationQuery;
use app\modules\warehouse\models\Warehouse;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%organization}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $file
 * @property integer $warehouse_id
 * @property integer $status
 * @property integer $sort
 */
class Organization extends \yii\db\ActiveRecord
{
    //Organization status
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    
    //Scenarios
    const SCENARIO_ADMIN_EDIT = 'admin_edit';
    const SCENARIO_EDITOR_EDIT = 'editor_edit';
    
    //File
    public $dataFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'address'], 'string', 'max' => 255],
            ['phone', 'string', 'max' => 100],
            ['latitude', 'number', 'min' => -90, 'max' => 90],
            ['longitude', 'number', 'min' => -180, 'max' => 180],
            ['info', 'safe'],
            ['warehouse_id', 'integer'],
            [['status', 'sort'], 'integer'],
            ['sort', 'default', 'value' => 0],
            [['dataFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, xlx, xlsx, doc, docx'],
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
        $scenarios[self::SCENARIO_DEFAULT] = ['title', 'address', 'phone', 'latitude', 'longitude', 'info'];
        $scenarios[self::SCENARIO_ADMIN_EDIT] = array_merge($scenarios[self::SCENARIO_DEFAULT], ['status', 'warehouse_id', 'dataFile', 'sort']);
        $scenarios[self::SCENARIO_EDITOR_EDIT] = array_merge($scenarios[self::SCENARIO_DEFAULT], ['dataFile', 'sort']);
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Module::t('organization', 'ORGANIZATION_TITLE'),
            'address' => Module::t('organization', 'ORGANIZATION_ADDRESS'),
            'phone' => Module::t('organization', 'ORGANIZATION_PHONE'),
            'latitude' => Module::t('organization', 'ORGANIZATION_LATITUDE'),
            'longitude' => Module::t('organization', 'ORGANIZATION_LONGITUDE'),
            'info' => Module::t('organization', 'ORGANIZATION_INFO'),
            'dataFile' => Module::t('organization', 'ORGANIZATION_FILE'),
            'status' => Module::t('organization', 'ORGANIZATION_STATUS'),
            'sort' => Module::t('organization', 'ORGANIZATION_SORT'),
            'warehouse_id' => Module::t('organization', 'ORGANIZATION_WAREHOUSE'),
        ];
    }
    
    /**
     * @inheritdoc
     * @return CropQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrganizationQuery(get_called_class());
    }
    
    /**
     * Block Organization
     * @return boolean
     */
    public function block(){
        $this->status = static::STATUS_DISABLED;
        return $this->save(false);
    }
    
    /**
     * Unblock Organization
     * @return boolean
     */
    public function unblock(){
        $this->status = static::STATUS_ACTIVE;
        return $this->save(false);
    }
    
    /**
     * Get Organization Status names array
     * 
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            static::STATUS_DISABLED => Module::t('organization', 'STATUS_DISABLED'),
            static::STATUS_ACTIVE => Module::t('organization', 'STATUS_ACTIVE'),
        ];
    }
    
    /**
     * Get Organization status name
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
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }
    
    /**
     * Upload data file
     * 
     * @param UploadedFile[] $files
     * @param string $path
     * @param boolean $generateNewFilename
     * @return boolean
     */
    public function upload(/*$files, */$path = '', $generateNewFilename = true)
    {
        // if no files was uploaded abort the upload
        if (empty($this->dataFile)) {
            return false;
        }
        // Задаем пути
        $saveDir = empty($path) ? '' : $path;
        $baseDir = \Yii::getAlias('@app/web/site/docs');
        // Если путь не существует - создаем его
        if (!is_dir($baseDir . DIRECTORY_SEPARATOR . $saveDir)){
            $oldmask = umask(0);
            mkdir($baseDir . DIRECTORY_SEPARATOR .$saveDir, 0777, true);
            umask($oldmask);
        }
        // Обрабатываем файлы
        //$result = [];
        //foreach ($files as $file) {
            // Новые имена и названия файлов
            //$fileTitle = $file->baseName;
            $fileName = $generateNewFilename ? \Yii::$app->security->generateRandomString() : $this->dataFile->baseName;
            // Сохраняем файлы на диск
            $this->dataFile->saveAs($baseDir . DIRECTORY_SEPARATOR . $saveDir . DIRECTORY_SEPARATOR . $fileName . '.' . $this->dataFile->extension);
            //Сохраняем путь к сохраненному файлу
            $this->file = '/docs/' . $saveDir . '/' . $fileName . '.' . $this->dataFile->extension;
            // Сохраняем модели в БД
            if (!$this->save(false)) {
                return false;
            }
            //$result[] = $newFile->id;
        //}

        return true;
    }
}
