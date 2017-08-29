<?php

namespace app\modules\site\controllers\backend;

use Yii;
use yii\web\Controller;
use app\modules\site\models\Page;
use vova07\imperavi\actions\GetAction;
use vova07\imperavi\actions\UploadAction;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'images-get' => [
                'class' => GetAction::className(),
                'url' => '/upload/', // Directory URL address, where files are stored.
                'path' => '@webroot/upload/', // Or absolute path to directory where files are stored.
                'type' => GetAction::TYPE_IMAGES,
            ],
            'image-upload' => [
                'class' => UploadAction::className(),
                'url' => '/upload/',
                'path' => '@webroot/upload/',
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($page = 0, $sub = 0)
    {
        $siteModel = new \app\modules\site\models\SiteModel($page, $sub);

        //Сохраняем изменения
        if ($siteModel->page->load(Yii::$app->request->post())) {
            $siteModel->page->save();
            Yii::$app->session->setFlash('success', 'Изменения сохранены');
        }
        if ($sub != 0) {
            if ($siteModel->sub->load(Yii::$app->request->post())) {
                $siteModel->sub->save();
                Yii::$app->session->setFlash('success', 'Изменения сохранены');
            }
        }
        
        return $this->render('index', [
                'model' => $siteModel,
            ]);
    }
}
