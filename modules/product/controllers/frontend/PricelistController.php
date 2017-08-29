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
        $product = null;
        $warehouse = null;
        if (Yii::$app->request->isPost) {
            $product = Yii::$app->request->post('product');
            $warehouse = Yii::$app->request->post('warehouse');
        }
        $prices = Yii::$app->user->identity->getActiveProductsAndWarehouses($product, $warehouse);
        /*echo '<pre>';
        var_dump($warehouse);
        die();*/
        $tableData = Product::generatePricesTable($prices['product'], $prices['warehouse']);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $tableData['data'],
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'columns' => $tableData['columns'],
            'products' => $prices['productAll'],
            'warehouses' => $prices['warehouseAll'],
            'selectedProduct' => is_array($product) ? $product[0] : $product,
            'selectedWarehouse' => is_array($warehouse) ? $warehouse[0] : $warehouse,
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
            return ['output' => $price->getPricesNoTax(false, ''), 'message' => $message];
        }
        
        return false;
    }
}
