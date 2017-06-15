<?php

namespace app\modules\warehouse\controllers\backend;

use Yii;
use app\modules\warehouse\models\Warehouse;
use app\modules\warehouse\models\search\WarehouseSearch;
use app\modules\group\models\Group;
use app\modules\product\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;

/**
 * DefaultController implements the CRUD actions for Group model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Warehouse models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WarehouseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Warehouse model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $warehouse = $this->findModel($id);
        $groups = new ArrayDataProvider([
            'allModels' => $warehouse->activeGroups,
            'sort' => false,
            'pagination' => false,
        ]);
        $products = new ArrayDataProvider([
            'allModels' => $warehouse->activeProducts,
            'sort' => false,
            'pagination' => false,
        ]);
        
        return $this->render('view', [
            'warehouse' => $warehouse,
            'groups' => $groups,
            'products' => $products,
        ]);
    }

    /**
     * Creates a new Warehouse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($view, $id = null)
    {
        $model = new Warehouse();
        $model->status = Warehouse::STATUS_ACTIVE;
        $model->scenario = Warehouse::SCENARIO_ADMIN_EDIT;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Warehouse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $view = 'view')
    {
        $model = $this->findModel($id);
        $model->scenario = Warehouse::SCENARIO_EDITOR_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Disable an existing Warehouse model.
     * @param integer $id
     * @return mixed
     */
    public function actionBlock($id, $view)
    {
        $model = $this->findModel($id);
        $model->block();

        return $this->redirect([$view, 'id' => $id]);
    } 

    /**
     * Enable an existing Warehouse model.
     * @param integer $id
     * @return mixed
     */
    public function actionUnblock($id, $view)
    {
        $model = $this->findModel($id);
        $model->unblock();

        return $this->redirect([$view, 'id' => $id]);
    } 
    
    /**
     * Finds the Warehouse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Warehouse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Warehouse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Manage Warehouse Groups
     * @param integer $id
     * @return string
     */
    public function actionGroups($id, $view = 'view')
    {
        $warehouse = $this->findModel($id);
        $warehouseGroups = $warehouse->preparedForSIWActiveGroups();
        $allGroups = Group::preparedForSIWActiveGroups();
        
        return $this->render('groups', [
                'warehouse' => $warehouse,
                'allGroups' => array_diff_key($allGroups, $warehouseGroups),
                'warehouseGroups' => $warehouseGroups,
                'view' => $view,
            ]);
    }
    
    /**
     * Manage Warehouse Products
     * @param integer $id
     * @return string
     */
    public function actionProducts($id, $view = 'view')
    {
        $warehouse = $this->findModel($id);
        $warehouseProducts = $warehouse->preparedForSIWActiveProducts();
        $allProducts = Product::preparedForSIWActiveProducts();
        
        return $this->render('products', [
                'warehouse' => $warehouse,
                'allProducts' => array_diff_key($allProducts, $warehouseProducts),
                'warehouseProducts' => $warehouseProducts,
                'view' => $view,
            ]);
    }
    
    /**
     * Ajax Groups managment
     * @param type $id
     * @return boolean
     */
    public function actionGroupChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $warehouse = $this->findModel($id);
            $groupsString = Yii::$app->request->post('groups');
            $warehouse->groupsList = empty($groupsString) ? [] : explode(',', $groupsString);
            
            return $warehouse->save(false);
        }
        
        return false;
    }
    
    /**
     * Ajax Products managment
     * @param type $id
     * @return boolean
     */
    public function actionProductChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $warehouse = $this->findModel($id);
            $productsString = Yii::$app->request->post('products');
            $warehouse->productsList = empty($productsString) ? [] : explode(',', $productsString);
            
            return $warehouse->save(false);
        }
        
        return false;
    }
}
