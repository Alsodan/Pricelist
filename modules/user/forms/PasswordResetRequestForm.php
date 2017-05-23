<?php

namespace app\modules\user\forms;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    
    private $_user = false;
    private $_timeout;
    
    public function __construct($timeout, $config = [])
    {
        $this->_timeout = $timeout;
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Module::t('user', 'USER_PASSWORD_RESET_NO_USER')
            ],
            ['email', 'validateIsSent']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Module::t('user', 'USER_EMAIL'),
        ];
    }
    
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token, $this->_timeout)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        return Yii::$app
            ->mailer
            ->compose('@app/modules/user/mails/passwordReset', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setReplyTo(Yii::$app->params['adminEmail'])
            ->setTo($this->email)
            ->setSubject(Module::t('user', 'USER_PASSWORD_RESET_MAIL_SUBJECT') . Yii::$app->name)
            ->send();
    }
    
    public function validateIsSent($attribute, $params)
    {
        if (!$this->hasErrors() && $user = $this->getUser()) {
            if (!User::isPasswordResetTokenValid($user->$attribute, $this->_timeout)) {
                $this->addError($attribute, Module::t('user', 'USER_PASSWORD_RESET_ERROR_TOKEN_IS_SENT'));
            }
        }
    }
    
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);
        }
        
        return $this->_user;
    }
}
