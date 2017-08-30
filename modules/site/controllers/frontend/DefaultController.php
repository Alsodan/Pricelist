<?php

namespace app\modules\site\controllers\frontend;

use yii\web\Controller;
use app\modules\site\models\SiteModel;
use kartik\mpdf\Pdf;

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
    
    //Output pricelist to PDF
    public function actionPricelistPdf($region = 0, $warehouse = 0, $crop = 0)
    {
        $site = new SiteModel('pricelist');
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'marginTop' => 26,
            'marginLeft' => 10,
            'marginRight' => 10,
            'content' => $this->renderPartial('pricelistPdf', [
                'site' => $site,
                'region' => $region,
                'warehouse' => $warehouse,
                'crop' => $crop,
            ]),
            'filename' => 'Прайслист ООО КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО (изменен ' . $site->generateLastChange(true) . ').pdf',
            'options' => [
                'title' => 'Прайслист ООО "КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО"',
                'subject' => 'PDF',
            ],
            'methods' => [
                'SetHeader' => ['<img src="/images/logo_0.png" width=200>||' . $site->generateLastChange()],
                'SetFooter' => ['|Страница {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }
}
