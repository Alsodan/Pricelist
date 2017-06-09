<?php

namespace app\modules\group\models;

use yii\db\ActiveRecord;
use app\modules\group\models\Group;
use app\modules\warehouse\models\Warehouse;

/**
 * This is the model class for table "{{%group_warehouses}}".
 *
 * @property integer $warehouse_id
 * @property integer $group_id
 *
 * @property Group $group
 * @property Warehouse $warehouse
 */
class GroupWarehouses extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_warehouses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'group_id'], 'integer'],
            [['warehouse_id', 'group_id'], 'unique', 'targetAttribute' => ['warehouse_id', 'group_id'], 'message' => 'The combination of Warehouse ID and Group ID has already been taken.'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }
}
