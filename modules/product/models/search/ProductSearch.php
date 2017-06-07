<?php

namespace app\modules\product\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\Product;
use yii\helpers\ArrayHelper;

/**
 * ProductSearch represents the model behind the search form about `app\modules\product\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'crop_id', 'grade'], 'integer'],
            [['price_no_tax', 'price_with_tax'], 'double'],
            [['title', 'subtitle'], 'string', 'max' => 100],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            //return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
    
    public function searchWithGroup()
    {
        $products = Yii::$app->user->identity->profile->activeProducts;
        
        $data = [];
        foreach ($products as $item) {
            $data[] = ArrayHelper::toArray($item, [
            Product::className() => [
                'id', 'title', 'subtitle', 'price_no_tax', 'price_with_tax'
            ]
            ]);
        }
        
        return $data;
    }
}
