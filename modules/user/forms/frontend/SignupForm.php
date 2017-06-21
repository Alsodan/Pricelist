<?php

namespace app\modules\user\forms\frontend;

use Yii;
use yii\base\Model;
use app\modules\user\models\common\User;
use app\modules\user\models\common\Profile;
use app\modules\user\Module;

/**
 * SignupForm is the model behind the sign up form.
 */
class SignupForm extends Model
{
    
    public $username;
    public $email;
    public $password;
    public $verifyCode;
    
    private $_defaultRole;
    
    public $name;
    public $phone;
    public $workEmail;
    
    public function __construct($defaultRole, $config = [])
    {
        $this->_defaultRole = $defaultRole;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'], //#^[a-zа-яё0-9_\-]+$#uis - With Russian letters
            ['username', 'unique', 'targetClass' => User::className(), 'message' => Module::t('user', 'USER_SIGN_UP_NOT_UNIQUE_USERNAME')],
            ['username', 'string', 'min' => 2, 'max' => 255],
 
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Module::t('user', 'USER_SIGN_UP_NOT_UNIQUE_EMAIL')],
 
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
 
            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
            
            [['name', 'phone', 'workEmail'], 'required'],
            
            ['workEmail', 'filter', 'filter' => 'trim'],
            ['workEmail', 'email'],
        ];
    }
 
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('user', 'USER_USERNAME'),
            'email' => Module::t('user', 'USER_EMAIL'),
            'password' => Module::t('user', 'USER_PASSWORD'),
            'verifyCode' => Module::t('user', 'USER_VERIFY_CODE'),
            'name' => Module::t('user', 'USER_PROFILE_NAME'),
            'phone' => Module::t('user', 'USER_PROFILE_PHONE'),
            'workEmail' => Module::t('user', 'USER_PROFILE_WORK_EMAIL'),
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->role = $this->_defaultRole;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();
 
            if ($user->save()) {
                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->phone = $this->phone;
                $profile->name = $this->name;
                $profile->work_email = $this->workEmail;
                
                $profile->save();
                
                Yii::$app->mailer->compose('@app/modules/user/mails/emailConfirm', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setReplyTo(Yii::$app->params['adminEmail'])
                    ->setTo($this->email)
                    ->setSubject(Module::t('user', 'USER_SIGN_UP_MAIL_SUBJECT') . $this->username)
                    ->send();
                return $user;
            }
        }
 
        return null;
    }
}
