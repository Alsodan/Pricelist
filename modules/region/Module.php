<?php

namespace app\modules\region;

use Yii;
use yii\filters\AccessControl;
use app\modules\admin\rbac\Rbac;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_ADMINISTRATION],
                    ],
                ],
            ],
        ];
    }
 
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/region/' . $category, $message, $params, $language);
    }
}
