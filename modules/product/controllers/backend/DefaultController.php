<?php

namespace app\modules\product\controllers\backend;

use Yii;
use app\modules\product\models\Product;
use app\modules\product\models\search\ProductSearch;
use app\modules\user\models\common\Profile;
use app\modules\warehouse\models\Warehouse;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;

/**
 * DefaultController implements the CRUD actions for Product model.
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $product = $this->findModel($id);
        
        $data = new ArrayDataProvider([
            'allModels' => $product->linkedDataArrayList,
            'sort' => false,
            'pagination' => false,
        ]);
        
        return $this->render('view', [
            'product' => $product,
            'data' => $data,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null, $view = 'view')
    {
        $model = new Product();
        $model->scenario = Product::SCENARIO_ADMIN_EDIT;
        $model->status = Product::STATUS_ACTIVE;
            
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => is_null($id) ? $model->id : $id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $view = 'view')
    {
        $model = $this->findModel($id);
        $model->scenario = Product::SCENARIO_EDITOR_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Disable an existing Product model.
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
     * Enable an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Manage Product Users
     * @param integer $id
     * @return string
     */
    public function actionUsers($id, $view = 'view')
    {
        $product = $this->findModel($id);
        $productUsers = $product->preparedForSIWActiveProfiles();
        $allUsers = Profile::preparedForSIWActiveProfiles();
        
        return $this->render('users', [
                'product' => $product,
                'allUsers' => array_diff_key($allUsers, $productUsers),
                'productUsers' => $productUsers,
                'view' => $view,
            ]);
    }
    
    /**
     * Manage Product Warehouses
     * @param integer $id
     * @return string
     */
    public function actionWarehouses($id, $view = 'view')
    {
        $product = $this->findModel($id);
        $productWarehouses = $product->preparedForSIWActiveWarehouses();
        $allWarehouses = Warehouse::preparedForSIWActiveWarehouses();
        
        return $this->render('warehouses', [
                'product' => $product,
                'allWarehouses' => array_diff_key($allWarehouses, $productWarehouses),
                'productWarehouses' => $productWarehouses,
                'view' => $view,
            ]);
    }
    
    /**
     * Manage Products Managers
     * @param type $id
     * @param type $view
     * @return type
     */
    public function actionProductsUsers($id, $view = 'view')
    {
        $model = $this->findModel($id);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->pricesTable['data'],
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('products-users', [
                'group' => $model,
                'dataProvider' => $dataProvider,
                'columns' => $model->pricesTable['columns'],
                'view' =>$view,
            ]);
    }
    
    /**
     * Ajax changing profilesList
     * @param type $id
     * @return boolean
     */
    public function actionUserChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $product = $this->findModel($id);
            $usersString = Yii::$app->request->post('users');
            $product->profilesList = empty($usersString) ? [] : explode(',', $usersString);
            
            return $product->save(false);
        }
        
        return false;
    }
    
    /**
     * Ajax changing warehousesList
     * @param type $id
     * @return boolean
     */
    public function actionWarehouseChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $product = $this->findModel($id);
            $warehousesString = Yii::$app->request->post('warehouses');
            $product->warehousesList = empty($warehousesString) ? [] : explode(',', $warehousesString);
            
            return $product->save(false);
        }
        
        return false;
    }    
}
