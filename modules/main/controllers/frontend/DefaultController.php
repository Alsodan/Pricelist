<?php

namespace app\modules\main\controllers\frontend;

use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    /*public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }*/
    
    public function actionError()
    {
        /*if (($exception = \Yii::$app->errorHandler->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        */
        
        $this->layout = \Yii::$app->user->isGuest ? '@app/views/layouts/site' : '@app/views/layouts/main';
        
        $exception = \Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            return $this->render('error', [
                'page' => new \app\modules\site\models\SiteModel('error'),
                'exception' => $exception,
                'name' => $exception->getName(),
                'message' => $exception->getMessage(),
                'statusCode' => $exception->statusCode,
            ]);
        }
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
