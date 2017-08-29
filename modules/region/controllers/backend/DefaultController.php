<?php

namespace app\modules\region\controllers\backend;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\region\models\Region;
use app\modules\region\models\search\RegionSearch;
use app\modules\warehouse\models\Warehouse;

/**
 * DefaultController implements the CRUD actions for Region model.
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
     * Lists all Region models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->pagination = false;

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Region model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $region = $this->findModel($id);
        
        return $this->render('view', [
            'region' => $region,
        ]);
    }

    /**
     * Creates a new Region model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($view = 'view', $id = null)
    {
        $model = new Region();
        $model->status = Region::STATUS_ACTIVE;
        $model->scenario = Region::SCENARIO_ADMIN_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Region model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($view = 'view', $id)
    {
        $model = $this->findModel($id);
        $model->scenario = Region::SCENARIO_ADMIN_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing Region model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Disable an existing Region model.
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
     * Enable an existing Region model.
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
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Crop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Region::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Manage Warehouses
     * @param integer $id
     * @return string
     */
    public function actionWarehouses($id, $view = 'view')
    {
        $region = $this->findModel($id);
        $regionWarehouses = $region->preparedForSIWActiveWarehouses();
        $allWarehouses = Warehouse::preparedForSIWActiveWarehousesNoRegion();
        
        return $this->render('warehouses', [
                'region' => $region,
                'allWarehouses' => array_diff_key($allWarehouses, $regionWarehouses),
                'regionWarehouses' => $regionWarehouses,
                'view' => $view,
            ]);
    }
    
    /**
     * Ajax Warehouse managment
     * @param type $id
     * @return boolean
     */
    public function actionWarehouseChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $region = $this->findModel($id);
            $currentWarehousesIds = \yii\helpers\ArrayHelper::getColumn($region->warehouses, 'id');
            $newWarehousesIds = explode(',', Yii::$app->request->post('warehouses'));
            
            /*Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [Warehouse::findOne(current(array_diff($newWarehousesIds, $currentWarehousesIds)))];*/
            if (count($currentWarehousesIds) > count($newWarehousesIds)) {
                //Warehouse was removed
                $warehouse = Warehouse::findOne(current(array_diff($currentWarehousesIds, $newWarehousesIds)));
                $warehouse->updateAttributes(['region_id' => null]);
                //$warehouse->save(false);
            }
            else {
                //Warehouse was added
                $warehouse = Warehouse::findOne(current(array_diff($newWarehousesIds, $currentWarehousesIds)));
                $warehouse->updateAttributes(['region_id' => $id]);
                /*$warehouse->region_id = $id;
                $warehouse->save(false);*/
            }
            
            return $warehouse->id;
        }
        
        return false;
    }
}
