<?php

namespace app\modules\user;

use Yii;
use app\modules\admin\rbac\Rbac;

/**
 * user module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRole = Rbac::ROLE_USER;
    
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
