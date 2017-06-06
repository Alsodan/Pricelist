<?php

namespace app\modules\product\models;

use \yii\db\ActiveRecord;
use app\modules\product\models\Product;
use app\modules\user\models\common\Profile;

/**
 * This is the model class for table "{{%profile_products}}".
 *
 * @property integer $profile_id
 * @property integer $product_id
 *
 * @property Product $product
 * @property Profile $profile
 */
class ProfileProducts extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile_products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', 'product_id'], 'integer'],
            [['profile_id', 'product_id'], 'unique', 'targetAttribute' => ['profile_id', 'product_id'], 'message' => 'The combination of Profile ID and Product ID has already been taken.'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
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
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }
}
