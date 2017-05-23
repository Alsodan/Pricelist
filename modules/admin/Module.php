<?php

namespace app\modules\admin;

use yii\filters\AccessControl;
use yii\console\Application as ConsoleApplication;
use Yii;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'app\modules\admin\commands';
        }
    }
    
    public function behaviors()
    {
        return array_filter([
            'access' => !Yii::$app instanceof \yii\console\Application ? [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ] : false,
        ]);
    }
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/admin/' . $category, $message, $params, $language);
    }
}
