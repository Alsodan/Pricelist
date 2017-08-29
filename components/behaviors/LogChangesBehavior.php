<?php

namespace app\components\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\base\ErrorException;
use app\components\behaviors\models\Changes;
use app\components\behaviors\models\Changelog;

/**
 * Class LogChangesBehavior
 * 
 * Сохраняет лог изменений и значения измененных полей
 * 
 * Параметры
 * eventsDefault - События по умолчанию
 * eventsOnly - События к которым необходимо прикрепить поведение. Если они не в массиве $eventsDefault - то отбрасываются
 * fields - Список полей, для которых отслеживаются изменения (по умолчанию - все аттрибуты модели)
 * Для использования других полей нужно переопределить 
 * format - Формат записи даты и времени в БД (по умолчанию => 'Y-m-d H:i:s')
 * objectType - Тип объекта для записи (по умолчанию => className())
 * objectTitle - Название объекта (по умолчанию => tableName())
 *
 */
class LogChangesBehavior extends \yii\base\Behavior
{
    /**
     * $eventsDefault - События по умолчанию
     * $eventsOnly - События к которым необходимо прикрепить поведение. Если они не в массиве $eventsDefault - то отбрасываются
     * $fields - Список полей, для которых отслеживаются изменения (по умолчанию - все)
     * $format - Формат записи даты и времени в БД (по умолчанию => 'Y-m-d H:i:s')
     * $objectType - Тип объекта для записи (по умолчанию => className())
     * $objectTitle - Название объекта (по умолчанию => tableName())
     * $registerUserId - ИД пользователя от которого создается объект, если пользователь не залогинился
     */
    private $eventsDefault = [
            ActiveRecord::EVENT_AFTER_INSERT  => 'newObject',
            ActiveRecord::EVENT_AFTER_UPDATE  => 'updateObject',
            //ActiveRecord::EVENT_BEFORE_DELETE => 'deleteObject'
        ];
    public $eventsOnly = [];
    public $fields = [];
    public $fieldName = '';
    public $format = 'Y-m-d H:i:s';
    public $objectType;
    public $objectTitle;
    public $registerUserId;
    public $addon = [
            ActiveRecord::EVENT_AFTER_INSERT  => 'создан(а)',
            ActiveRecord::EVENT_AFTER_UPDATE  => 'изменен(а)',
            //ActiveRecord::EVENT_BEFORE_DELETE => 'удален(а)'
    ];
    private $oldFields;
    
    /**
     * Список событий для поведения
     * с учетом указанных в модели
     * @return array
     */
    public function events()
    {
        $events = !empty($this->eventsOnly) ? array_intersect_key($this->eventsDefault, array_flip($this->eventsOnly)) : $this->eventsDefault;
        if (!empty($events)) {
            $events[ActiveRecord::EVENT_AFTER_FIND] = 'foundObject';
        }
        return $events;
    }
    
    /**
     * Сохраняет все новые поля при создании нового объекта в БД
     * @param $event
     * @throws ErrorException
     * @throws \yii\db\Exception
     */
    public function newObject($event)
    {
        $title = empty($this->objectTitle) ? $this->owner->tableName() : $this->objectTitle;
        $title .= ' ' . $this->addon[ActiveRecord::EVENT_AFTER_INSERT];
        
        $this->saveChanges($title, true);
        
        $this->oldFields = $this->getOwnerAttributes();
    }
    
    /**
     * Сохраняет все измененные поля при сохранении объекта в БД
     * @param $event
     * @throws ErrorException
     * @throws \yii\db\Exception
     */
    public function updateObject($event)
    {
        $title = empty($this->objectTitle) ? $this->owner->tableName() : $this->objectTitle;
        $title .= ' ' . $this->addon[ActiveRecord::EVENT_AFTER_UPDATE];
        
        $this->saveChanges($title, false);
    }
    
    /**
     * Сохраняет пользовательское изменение
     * @param string $title - текст пользовательского изменения
     * @throws ErrorException
     * @throws \yii\db\Exception
     */
    public function customChange($titleAddon = '', $onlyAddon = false, $useSystem = false)
    {
        if ($onlyAddon) {
            $this->saveChanges($onlyAddon, false, false, $useSystem);
        }
        else {
            $title = empty($this->objectTitle) ? $this->owner->tableName() : $this->objectTitle;
            $title .= ' ' . $titleAddon;
            $this->saveChanges($title, false, false, $useSystem);
        }
    }
    
    /**
     * Сохраняет запись об изменении объекта
     * @param string $title Причина изменения (новый/изменен/удален)
     * @param boolean $isNew Новый объект
     * @param boolean $needLog Нужно сохранить лог
     * @throws ErrorException
     * @throws \yii\db\Exception
     */    
    private function saveChanges($title, $isNew, $needLog = true, $useSystem = false){
        // Начало транзакции
        $transaction = Yii::$app->db->beginTransaction();

        $user = 0;
        if (!$useSystem){
            $user = \Yii::$app->user->isGuest ? $this->registerUserId : \Yii::$app->user->identity->id;
        }
        try {
            $change = new Changes([
                'date' => date($this->format, strtotime('now +3 hour')),
                'object_type' => empty($this->objectType) ? $this->owner->className() : $this->objectType,
                'object_id' => $this->owner->id,
                'user_id' => $user,
                'title' => $title
            ]);
            $change->save();
           
            // Совершаем транзакцию 
            $transaction->commit();
        } 
        catch (\yii\db\Exception $ex) {
            // Откатываем транзакцию
            $transaction->rollback();
            throw $ex;
        }
        
        if ($needLog) $this->saveLog($change, $isNew);
    }
    
    /**
     * Сохраняет все изменные поля объекта
     * @param string $change изменения, для которого сохраняем лог
     * @param boolean $isNew Если новый объект - не нужно вычислять измененные поля
     * @throws ErrorException
     * @throws \yii\db\Exception
     */      
    private function saveLog($change, $isNew){
        // Начало транзакции
        $transaction = Yii::$app->db->beginTransaction();
        $changeId = $change->id;
        
        try {
            // Массив непустых значений для пакетной записи
            $fields = [];
            $data = $isNew ? $this->getOwnerAttributes() : $this->getChangedAttributes();
            foreach ($data as $key => $value) {
                $fields[] = [
                    'change_id' => $changeId,
                    'field' => $key,
                    'field_text' => !empty($this->fieldName) ? $this->fieldName : $this->owner->attributeLabels[$key],
                    'old_value' => $isNew ? null : $this->oldFields[$key],
                    'new_value' => $value
                ];
            }
            
            //Пакетная вставка записей
            if (count($fields)) {
                Yii::$app->db->createCommand()
                    ->batchInsert(Changelog::tableName(), array_flip(Changelog::getAttributesList()), $fields)
                    ->execute();
            }
            else $change->delete();

            // Совершаем транзакцию 
            $transaction->commit();
        }
        catch (\yii\db\Exception $ex) {
            // Откатываем транзакцию
            $transaction->rollback();
            throw $ex;
        }
    }

    //Возвращает массив элементов, которые изменились с момента загрузки модели из БД
    private function getChangedAttributes(){
        return array_diff_assoc($this->getOwnerAttributes(), $this->oldFields);
    }

    /**
     * Сохраняет исходные поля при считывании объекта из БД
     * @param $event
     * @throws ErrorException
     * @throws \yii\db\Exception
     */
    public function foundObject($event)
    {
        $this->oldFields = $this->getOwnerAttributes();
    }
    
    /**
     * Возвращает массив аттрибутов модели в соответствии с массивом полей
     * Если он пустой - возвращаются все аттрибуты
     */
    public function getOwnerAttributes(){
        return $this->owner->toArray($this->fields);
    }
}
