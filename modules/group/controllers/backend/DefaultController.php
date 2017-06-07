<?php

namespace app\modules\group\controllers\backend;

use Yii;
use app\modules\group\models\Group;
use app\modules\group\models\search\GroupSearch;
use app\modules\user\models\common\Profile;
use app\modules\warehouse\models\Warehouse;
use app\modules\product\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

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
     * Lists all Group models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Group model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $group = $this->findModel($id);
        $users = new ArrayDataProvider([
            'allModels' => $group->activeProfiles,
            'sort' => false,
            'pagination' => false,
        ]);
        $warehouses = new ArrayDataProvider([
            'allModels' => $group->activeWarehouses,
            'sort' => false,
            'pagination' => false,
        ]);
        $products = new ArrayDataProvider([
            'allModels' => $group->activeProducts,
            'sort' => false,
            'pagination' => false,
        ]);  
        
        return $this->render('view', [
            'group' => $group,
            'users' => $users,
            'warehouses' => $warehouses,
            'products' => $products,
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group();
        $model->status = Group::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Group model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Disable an existing Group model.
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
     * Enable an existing Group model.
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
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Manage Group Users
     * @param integer $id
     * @return string
     */
    public function actionUsers($id, $view = 'view')
    {
        $group = $this->findModel($id);
        $groupUsers = $group->preparedForSIWActiveProfiles();
        $allUsers = Profile::preparedForSIWActiveProfiles();
        
        return $this->render('users', [
                'group' => $group,
                'allUsers' => array_diff_key($allUsers, $groupUsers),
                'groupUsers' => $groupUsers,
                'view' => $view,
            ]);
    }

    /**
     * Manage Group Warehouses
     * @param integer $id
     * @return string
     */
    public function actionWarehouses($id, $view = 'view')
    {
        $group = $this->findModel($id);
        $groupWarehouses = $group->preparedForSIWActiveWarehouses();
        $allWarehouses = Warehouse::preparedForSIWActiveWarehouses();
        
        return $this->render('warehouses', [
                'group' => $group,
                'allWarehouses' => array_diff_key($allWarehouses, $groupWarehouses),
                'groupWarehouses' => $groupWarehouses,
                'view' => $view,
            ]);
    }
    
    /**
     * Manage Group Products
     * @param integer $id
     * @return string
     */
    public function actionProducts($id, $view = 'view')
    {
        $group = $this->findModel($id);
        $groupProducts = $group->preparedForSIWActiveProducts();
        $allProducts = Product::preparedForSIWActiveProducts();
        
        return $this->render('products', [
                'group' => $group,
                'allProducts' => array_diff_key($allProducts, $groupProducts),
                'groupProducts' => $groupProducts,
                'view' => $view,
            ]);
    }
    
    /**
     * Manage Groups Users
     * @return string
     */
    /*public function actionUsers0($id = -1)
    {
        $groups = ArrayHelper::map(Group::find()->select(['id', 'title'])->asArray()->all(), 'id', 'title');
        if ($id == -1) {
            $id = key($groups);
        }
        
        return $this->render('user', [
                'groups' => $groups,
                'selectedGroup' => $id,
            ]);
    }*/
    
    /**
     * Ajax Users management
     * @param type $id
     * @return boolean
     */
    public function actionUserChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $group = $this->findModel($id);
            $usersString = Yii::$app->request->post('users');
            $group->profilesList = empty($usersString) ? [] : explode(',', $usersString);
            
            return $group->save(false);
        }
        
        return false;
    }
    
    /**
     * Ajax Warehouses managment
     * @param type $id
     * @return boolean
     */
    public function actionWarehouseChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $group = $this->findModel($id);
            $warehousesString = Yii::$app->request->post('warehouses');
            $group->warehousesList = empty($warehousesString) ? [] : explode(',', $warehousesString);
            
            return $group->save(false);
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
            $group = $this->findModel($id);
            $productsString = Yii::$app->request->post('products');
            $group->productsList = empty($productsString) ? [] : explode(',', $productsString);
            
            return $group->save(false);
        }
        
        return false;
    }     
}
