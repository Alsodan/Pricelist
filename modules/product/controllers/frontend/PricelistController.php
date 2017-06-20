<?php

namespace app\modules\product\controllers\frontend;

use Yii;
use app\modules\product\models\Product;
use app\modules\product\models\Price;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\web\Response;

class PricelistController extends Controller
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $prices = Yii::$app->user->identity->activeProductsAndWarehouses;
        $tableData = Product::generatePricesTable($prices['product'], $prices['warehouse']);
        //echo '<pre>';var_dump($tableData); die();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $tableData['data'],
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'columns' => $tableData['columns'],
        ]);
    }
            
    /**
     * Ajax Product Prices managment
     * @param type $id
     * @return boolean
     */
    public function actionProductPricesChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $price = Price::findOne($id);
            $price->load(Yii::$app->request->post());
            $price->call_no_tax = (bool)Yii::$app->request->post('call_no_tax');
            $price->call_with_tax = (bool)Yii::$app->request->post('call_with_tax');
            $price->noneed_no_tax = (bool)Yii::$app->request->post('noneed_no_tax');
            $price->noneed_with_tax = (bool)Yii::$app->request->post('noneed_with_tax');
            $price->save();
            $message = $price->errors;
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['output' => $price->prices, 'message' => $message];
        }
        
        return false;
    }
}
