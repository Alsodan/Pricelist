<?php

namespace app\modules\site;

use Yii;
use app\modules\admin\rbac\Rbac;
use yii\filters\AccessControl;

/**
 * main module definition class
 */
class Module extends \yii\base\Module
{
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/site/' . $category, $message, $params, $language);
    }
}
