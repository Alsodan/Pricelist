<?php

namespace app\modules\group\controllers\frontend;

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
use app\modules\group\Module;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

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
        $group = $this->findModel($id);
        $groupUsers = $group->preparedForSIWActiveProfiles();
        $allUsers = Profile::preparedForSIWActiveProfiles();
        
        return $this->render('users', [
                'group' => $group,
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
        $group = $this->findModel($id);
        $groupWarehouses = $group->preparedForSIWActiveWarehouses();
        $allWarehouses = Warehouse::preparedForSIWActiveWarehouses();
        
        return $this->render('warehouses', [
                'group' => $group,
                'allWarehouses' => array_diff_key($allWarehouses, $groupWarehouses),
                'groupWarehouses' => $groupWarehouses,
            ]);
    }
    
    /**
     * Manage Group Products
     * @param integer $id
     * @return string
     */
    public function actionProducts($id, $wh = null)
    {
        $group = $this->findModel($id);
        $groupProducts = $group->preparedForSIWActiveProducts();
        $allProducts = Product::preparedForSIWActiveProducts();
        $warehouses = ArrayHelper::map($group->warehouses, 'id', 'title');
        
        if (is_null($wh)) {
            $wh = key($warehouses);
        }
        
        return $this->render('products', [
                'group' => $group,
                'allProducts' => array_diff_key($allProducts, $groupProducts),
                'groupProducts' => $groupProducts,
                'warehouses' => $warehouses,
            ]);
    }
    
    /**
     * Manage Group
     * @param integer $id
     * @return string
     */
    public function actionManage($id)
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
        
        return $this->render('manage', [
            'group' => $group,
            'users' => $users,
            'warehouses' => $warehouses,
            'products' => $products,
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
