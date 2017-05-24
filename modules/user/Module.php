<?php

namespace app\modules\user;

use Yii;

/**
 * user module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @var int
     */
    public $passwordResetTokenExpire = 3600;
    
    /**
     * @var int
     */
    public $emailConfirmTokenExpire = 259200; // 3 days
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }
}
