<?php

namespace app\api\modules\v1\controllers;

use yii\rest\Controller;
use app\modules\product\models\Price;
use app\modules\warehouse\models\Warehouse;
use app\modules\product\models\Product;

class PriceController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter' ] = [
              'class' => \yii\filters\Cors::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        return $behaviors;
    }
    
    public function actionHello()
    {
        return ['a'=>'Hello from REST + яяяя'];
    }
    
    public function actionPrices($active = true)
    {
        return Price::find()
                    ->joinWith('product')
                    ->joinWith('warehouse')
                    ->where([Product::tableName() . '.status' => Product::STATUS_ACTIVE, Warehouse::tableName() . '.status' => Warehouse::STATUS_ACTIVE])
                    ->all();
    }
}
