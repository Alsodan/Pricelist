<?php

namespace app\components\behaviors\models;

use Yii;

/**
 * This is the model class for table "changes".
 *
 * @property integer $id
 * @property string $date
 * @property string $object_type
 * @property integer $object_id
 * @property integer $user_id
 * @property string $title
 *
 * @property Changelog $changelog
 */
class Changes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%changes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'object_type', 'object_id', 'user_id', 'title'], 'required'],
            [['date'], 'safe'],
            [['object_id', 'user_id'], 'integer'],
            [['object_type', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'object_type' => Yii::t('app', 'Object Type'),
            'object_id' => Yii::t('app', 'Object ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangelog()
    {
        return $this->hasMany(Changelog::className(), ['change_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\api\modules\v1\models\User::className(), ['id' => 'user_id']);
    }
    
    public static function getLastChangeDate(){
        return date('d.m.Y H:i:s', strtotime(Changes::find()->max('date') ));
    }
    
    public static function findByGroup($groupId, $type, $className)
    {
        $limits = $className::findByGroup($groupId);

        return Changes::find()
                ->select('*')
                ->with('changelog')
                ->with('user')
                ->with('user.profile')
                ->where(['object_type' => $type])
                ->andWhere(['object_id' => \yii\helpers\ArrayHelper::getColumn($limits->all(), 'id')]);
    }
}
