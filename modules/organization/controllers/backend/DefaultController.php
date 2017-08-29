<?php

namespace app\modules\organization\controllers\backend;

use Yii;
use app\modules\crop\models\Crop;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\data\ArrayDataProvider;

use app\modules\organization\models\Organization;
use app\modules\organization\models\search\OrganizationSearch;
use yii\web\UploadedFile;

/**
 * DefaultController implements the CRUD actions for Organization model.
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
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrganizationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->pagination = false;

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organization model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $organization = $this->findModel($id);
        
        return $this->render('view', [
            'organization' => $organization,
        ]);
    }

    /**
     * Creates a new Organization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($view = 'view', $id = null)
    {
        $model = new Organization();
        $model->status = Organization::STATUS_ACTIVE;
        $model->scenario = Organization::SCENARIO_ADMIN_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                $model->dataFile = UploadedFile::getInstance($model, 'dataFile');
                $model->upload($model->id);
            }
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Organization model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($view = 'view', $id)
    {
        $model = $this->findModel($id);
        $model->scenario = Organization::SCENARIO_ADMIN_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isPost) {
                $model->dataFile = UploadedFile::getInstance($model, 'dataFile');
                $model->upload($model->id);
            }
            return $this->redirect([$view, 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing Organization model.
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
     * Disable an existing Organization model.
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
     * Enable an existing Organization model.
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
     * Finds the Organization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Crop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organization::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
