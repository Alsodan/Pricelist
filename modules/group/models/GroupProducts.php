<?php

namespace app\modules\group\models;

use yii\db\ActiveRecord;
use app\modules\group\models\Group;
use app\modules\product\models\Product;

/**
 * This is the model class for table "{{%group_products}}".
 *
 * @property integer $product_id
 * @property integer $group_id
 *
 * @property Group $group
 * @property Product $product
 */
class GroupProducts extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'group_id'], 'integer'],
            [['product_id', 'group_id'], 'unique', 'targetAttribute' => ['product_id', 'group_id'], 'message' => 'The combination of Product ID and Group ID has already been taken.'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
