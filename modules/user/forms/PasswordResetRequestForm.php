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
        if (!$this->user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($this->user->password_reset_token, $this->_timeout)) {
            $this->user->generatePasswordResetToken();
            if (!$this->user->save()) {
                return false;
            }
        }
        return Yii::$app
            ->mailer
            ->compose('@app/modules/user/mails/passwordReset', ['user' => $this->user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setReplyTo(Yii::$app->params['adminEmail'])
            ->setTo($this->email)
            ->setSubject(Module::t('user', 'USER_PASSWORD_RESET_MAIL_SUBJECT') . Yii::$app->name)
            ->send();
    }
    
    /**
     * Check if Password Reset Token was already sent
     * @param type $attribute
     * @param type $params
     */
    public function validateIsSent($attribute, $params)
    {
        if (!$this->hasErrors() && $user = $this->getUser()) {
            if (User::isPasswordResetTokenValid($user->password_reset_token, $this->_timeout)) {
                $this->addError($attribute, Module::t('user', 'USER_PASSWORD_RESET_ERROR_TOKEN_IS_SENT'));
            }
        }
    }
    
    /**
     * Get user via email
     * @return null|mixed
     */
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
