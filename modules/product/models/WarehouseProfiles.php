<?php

namespace app\modules\warehouse\models;

use \yii\db\ActiveRecord;
use app\modules\warehouse\models\Warehouse;
use app\modules\user\models\common\Profile;

/**
 * This is the model class for table "{{%warehouse_profiles}}".
 *
 * @property integer $profile_id
 * @property integer $warehouse_id
 *
 * @property Warehouse $warehouse
 * @property Profile $profile
 */
class WarehouseProfiles extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse_profiles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', '$warehouse_id'], 'integer'],
            [['profile_id', '$warehouse_id'], 'unique', 'targetAttribute' => ['profile_id', '$warehouse_id'], 'message' => 'The combination of Profile ID and Warehouse ID has already been taken.'],
            [['$warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['$warehouse_id' => 'id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => '$warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }
}
