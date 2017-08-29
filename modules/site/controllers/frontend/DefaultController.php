<?php

namespace app\modules\site\controllers\frontend;

use yii\web\Controller;
use app\modules\site\models\SiteModel;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionPricelist()
    {
        return $this->render('pricelist', [
            'site' => new SiteModel('pricelist'),
        ]);
    }
    
    public function actionWarehouses()
    {
        return $this->render('warehouses', [
            'site' => new SiteModel('warehouses'),
        ]);
    }
    
    public function actionWarehouse($id)
    {
        return $this->render('warehouse', [
            'site' => new SiteModel('warehouses', $id),
            'model' => \app\api\modules\v1\models\Organization::findOne($id),
        ]);
    }
    
    public function actionSupplier()
    {
        return $this->render('supplier', [
            'site' => new SiteModel('supplier'),
        ]);
    }
    
    public function actionProducts()
    {
        return $this->render('products', [
            'site' => new SiteModel('products'),
        ]);
    }
    
    public function actionProduct($id)
    {
        return $this->render('product', [
            'site' => new SiteModel('products', $id),
            'model' => \app\api\modules\v1\models\Crop::findOne($id),
        ]);
    }
    
    public function actionContacts()
    {
        return $this->render('contacts', [
            'site' => new SiteModel('contacts'),
        ]);
    }    
}
