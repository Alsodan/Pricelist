<?php

namespace app\modules\product\models;

use \yii\db\ActiveRecord;
use app\modules\product\models\Product;
use app\modules\group\models\Group;

/**
 * This is the model class for table "{{%product_groups}}".
 *
 * @property integer $group_id
 * @property integer $product_id
 *
 * @property Product $product
 * @property Group $group
 */
class ProductGroups extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_groups}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'product_id'], 'integer'],
            [['group_id', 'product_id'], 'unique', 'targetAttribute' => ['group_id', 'product_id'], 'message' => 'The combination of Group ID and Product ID has already been taken.'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }
}
