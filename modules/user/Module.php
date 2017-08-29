<?php

namespace app\modules\user;

use Yii;
use app\modules\admin\rbac\Rbac;
use yii\filters\AccessControl;

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
    
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::ROLE_USER, Rbac::PERMISSION_PAGE_EDIT],
                    ],
                ],
            ],
        ];
    }*/
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }
}
