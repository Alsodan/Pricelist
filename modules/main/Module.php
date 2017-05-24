<?php

namespace app\modules\main;

use Yii;

/**
 * main module definition class
 */
class Module extends \yii\base\Module
{
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/main/' . $category, $message, $params, $language);
    }
}
