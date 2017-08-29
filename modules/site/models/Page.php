<?php

namespace app\modules\site\models;

use yii\db\ActiveRecord;
use app\modules\site\Module;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $product_id
 * @property integer $group_id
 *
 * @property Group $group
 * @property Product $product
 */
class Page extends ActiveRecord
{
    //Конструктор
    public function __construct($id = 0) {
        parent::__construct();
        $this->id = $id;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'meta_description', 'meta_keywords', 'header'], 'string', 'max' => 255],
            [['subheader', 'content'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'header' => Module::t('site', 'HEADER'),
            'subheader' => Module::t('site', 'SUBHEADER'),
            'content' => Module::t('site', 'CONTENT'),
        ];
    }
    
    /*
     * Поиск страницы по ID
     */
    public static function findPage($id, $sub)
    {
        //Если не задан $id страницы, берем первую из меню
        if ($id === 0) {
            $id = key(\app\modules\site\models\SiteModel::$menu);
        }
        //Добавляем подменю
        $id .= '_' . $sub;

        //Ищем страницу в БД
        $page = static::findOne($id);

        //Если нет - создаем
        if (is_null($page)) {
            $page = new Page($id);
        }
        
        return $page;
    }
}
