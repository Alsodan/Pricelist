<?php

namespace app\modules\user\controllers\backend;

use Yii;
use yii\web\Controller;
use app\modules\user\models\backend\search\UserSearch;

/**
 * Description of RolesController
 *
 * @author chebotarevae
 */
class RolesController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
