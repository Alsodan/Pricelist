<?php

namespace app\modules\crop\controllers\backend;

use Yii;
use app\modules\crop\models\Crop;
use app\modules\crop\models\search\CropSearch;
use app\modules\user\models\common\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;

/**
 * DefaultController implements the CRUD actions for Crop model.
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
        $searchModel = new CropSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->pagination = false;

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Crop model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $crop = $this->findModel($id);
        
        return $this->render('view', [
            'crop' => $crop,
        ]);
    }

    /**
     * Creates a new Crop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($view, $id = null)
    {
        $model = new Crop();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Crop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($view = 'view', $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Disable an existing Crop model.
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
     * Enable an existing Crop model.
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
     * Finds the Crop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Crop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Crop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Manage Crop Users
     * @param integer $id
     * @return string
     */
    public function actionUsers($id, $view = 'view')
    {
        $crop = $this->findModel($id);
        $cropUsers = $crop->preparedForSIWActiveProfiles();
        $allUsers = Profile::preparedForSIWActiveProfiles();
        
        return $this->render('users', [
                'crop' => $crop,
                'allUsers' => array_diff_key($allUsers, $cropUsers),
                'warehouseUsers' => $cropUsers,
                'view' => $view,
            ]);
    }
    
    public function actionUserChange($id)
    {
        if (Yii::$app->request->isAjax) {
            $crop = $this->findModel($id);
            $usersString = Yii::$app->request->post('users');
            $crop->profilesList = empty($usersString) ? [] : explode(',', $usersString);
            
            return $crop->save(false);
        }
        
        return false;
    }
}
