<?php

namespace app\modules\group;

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
                        'roles' => [Rbac::PERMISSION_GROUP_EDIT],
                    ],
                ],
            ],
        ];
    }
 
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/group/' . $category, $message, $params, $language);
    }
}
