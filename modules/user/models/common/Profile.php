<?php

namespace app\modules\user\models\common;

use Yii;
use yii\db\ActiveRecord;
use app\modules\user\models\common\User;
use \app\modules\user\models\common\query\ProfileQuery;
use app\modules\user\Module;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $work_email
 * @property integer $user_id
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name', 'phone', 'work_email'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('profile', 'USER_PROFILE_NAME'),
            'phone' => Module::t('profile', 'USER_PROFILE_PHONE'),
            'work_email' => Module::t('profile', 'USER_PROFILE_WORK_EMAIL'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\common\query\ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }
}
