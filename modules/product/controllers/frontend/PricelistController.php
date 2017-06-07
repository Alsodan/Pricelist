<?php

namespace app\modules\product\controllers\frontend;

use Yii;
use app\modules\product\models\Product;
use app\modules\product\models\search\ProductSearch;
use app\modules\user\models\common\Profile;
use app\modules\warehouse\models\Warehouse;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;

class PricelistController extends Controller
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $searchModel->searchWithGroup(),
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
