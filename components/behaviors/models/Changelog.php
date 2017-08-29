<?php

namespace app\components\behaviors\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "changelog".
 *
 * @property integer $id
 * @property integer $change_id
 * @property string $field
 * @property string $old_value
 * @property string $new_value
 *
 * @property Changes $change
 */
class Changelog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%changelog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['change_id', 'field', 'field_text', 'old_value', 'new_value'], 'required'],
            [['change_id'], 'integer'],
            [['field', 'field_text', 'old_value', 'new_value'], 'string', 'max' => 255],
            [['change_id'], 'exist', 'skipOnError' => true, 'targetClass' => Changes::className(), 'targetAttribute' => ['change_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'change_id' => Yii::t('app', 'Change ID'),
            'field' => Yii::t('app', 'Field'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
        ];
    }

    public static function getAttributesList() {
        return array_flip([
            'change_id',
            'field',
            'field_text',
            'old_value',
            'new_value'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChange()
    {
        return $this->hasOne(Changes::className(), ['id' => 'change_id']);
    }
}
