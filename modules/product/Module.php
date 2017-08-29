<?php

namespace app\modules\product;

use Yii;
use yii\filters\AccessControl;
use app\modules\admin\rbac\Rbac;

/**
 * product module definition class
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
                        'roles' => [Rbac::PERMISSION_PRICE_EDIT],
                    ],
                ],
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/product/' . $category, $message, $params, $language);
    }
}
