<?php

namespace app\modules\group\controllers\frontend;

use Yii;
use app\modules\group\models\Group;
use app\modules\user\models\common\Profile;
use app\modules\warehouse\models\Warehouse;
use app\modules\product\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\modules\group\Module;
use yii\helpers\ArrayHelper;
use app\components\behaviors\models\Changes;

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
     * Updates an existing Group model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Group::SCENARIO_EDITOR_EDIT;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('group', 'GROUP_EDIT_SUCCESS'));
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
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
    public function actionUsers($id)
    {
        $model = $this->findModel($id);
        $groupUsers = $model->preparedForSIWActiveUsers();
        $allUsers = Profile::preparedForSIWActiveProfiles();
        
        return $this->render('users', [
                'model' => $model,
                'allUsers' => array_diff_key($allUsers, $groupUsers),
                'groupUsers' => $groupUsers,
            ]);
    }

    /**
     * Manage Group Warehouses
     * @param integer $id
     * @return string
     */
    public function actionWarehouses($id)
    {
        $model = $this->findModel($id);
        $groupWarehouses = $model->preparedForSIWActiveWarehouses();
        $allWarehouses = Warehouse::preparedForSIWActiveWarehouses();
        
        return $this->render('warehouses', [
                'model' => $model,
                'allWarehouses' => array_diff_key($allWarehouses, $groupWarehouses),
                'groupWarehouses' => $groupWarehouses,
            ]);
    }
    
    /**
     * Manage Group Products
     * @param integer $id
     * @return string
     */
    public function actionGroupProducts($id)
    {
        $model = $this->findModel($id);
        $groupProducts = $model->preparedForSIWActiveGroupProducts();
        $allProducts = Product::preparedForSIWActiveProducts();
        
        return $this->render('group-products', [
                'model' => $model,
                'allProducts' => array_diff_key($allProducts, $groupProducts),
                'groupProducts' => $groupProducts,
            ]);
    }
    
    /**
     * Manage Group Warehouses Products
     * @param integer $id
     * @return string
     */
    public function actionProducts($id, $wh = null)
    {
        $model = $this->findModel($id);
        $warehouses = ArrayHelper::map($model->warehouses, 'id', 'title');
        
        /* If empty Warehouse ID, take first in Group */
        if (is_null($wh)) {
            $wh = key($warehouses);
        }
        
        $groupProducts = $model->preparedForSIWActiveProducts($wh);
        $allProducts = Product::preparedForSIWActiveProducts($id);
        
        return $this->render('products', [
                'model' => $model,
                'allProducts' => array_diff_key($allProducts, $groupProducts),
                'groupProducts' => $groupProducts,
                'warehouses' => $warehouses,
                'selectedWarehouse' => $wh,
            ]);
    }
    
    /**
     * Manage Group
     * @param integer $id
     * @return string
     */
    public function actionManage($id)
    {
        $model = $this->findModel($id);
        $users = new ArrayDataProvider([
            'allModels' => $model->activeUsers,
            'sort' => false,
            'pagination' => false,
        ]);
        $warehouses = new ArrayDataProvider([
            'allModels' => $model->activeWarehouses,
            'sort' => false,
            'pagination' => false,
        ]);
        $products = new ArrayDataProvider([
            'allModels' => $model->activeProducts,
            'sort' => false,
            'pagination' => false,
        ]);
        
        return $this->render('manage', [
            'model' => $model,
            'users' => $users,
            'warehouses' => $warehouses,
            'products' => $products,
        ]);
    }
    
    /**
     * Manage Products Users
     * @param type $id
     * @return type
     */
    public function actionProductsUsers($id)
    {
        $model = $this->findModel($id);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->usersTable['data'],
            'pagination' => false,
            'sort' => false,
        ]);

        $this->layout = '@app/views/layouts/product';
        return $this->render('products-users', [
                'group' => $model,
                'dataProvider' => $dataProvider,
                'columns' => $model->usersTable['columns'],
            ]);
    }
    
    /**
     * Manage Products Users
     * @param type $id
     * @return type
     */
    public function actionLog($id)
    {
        $model = $this->findModel($id);
        $query = Changes::findByGroup($id, \app\modules\product\models\Price::className(), \app\api\modules\v1\models\Price::className());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ],
            ]
        ]);

        return $this->render('log', [
                'group' => $model,
                'dataProvider' => $dataProvider,
            ]);
    }
    
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
            $group->usersList = empty($usersString) ? [] : explode(',', $usersString);
            
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
    public function actionProductChange($id, $wh)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_numeric($id)) {
            $id = 0;
        }
        if (!is_numeric($wh)) {
            $wh = 0;
        }
        if (Yii::$app->request->isAjax) {
            $group = $this->findModel($id);
            $productsString = Yii::$app->request->post('products');
            $warehouse = Warehouse::findOne((int)$wh);
            $oldProductList = $warehouse->productsList;
            $productsList = empty($productsString) ? [] : explode(',', $productsString);
            $otherProductsList = array_diff($oldProductList, ArrayHelper::getColumn($group->getActiveProducts($wh), 'id'));

            $warehouse->productsList = array_merge($otherProductsList, $productsList);

            return $warehouse->save(false);
        }
        
        return false;
    }
    
    /**
     * Ajax Price managment
     * @param type $id
     * @return boolean
     */
    public function actionProductUsersChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $price = \app\modules\product\models\Price::findOne($id);
            $usersString = Yii::$app->request->post('users');
            $price->usersList = empty($usersString) ? [] : $usersString;
            $price->save();
            $message = $price->errors;
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['output' => empty($price->activeUsersNames) ? Module::t('group', 'NO_USERS') : implode(', ', $price->activeUsersNames), 'message' => $message];
        }
        
        return false;
    }
    
    /**
     * Ajax Group Products managment
     * @param type $id
     * @return boolean
     */
    public function actionGroupProductChange($id)
    {
        if (!is_numeric($id)) {
            $id = 0;
        }

        if (Yii::$app->request->isAjax) {
            $group = $this->findModel((int)$id);
            $productsString = Yii::$app->request->post('products');
            $group->productsList = empty($productsString) ? [] : explode(',', $productsString);
            
            return $group->save(false);
        }
        
        return false;
    }
}
