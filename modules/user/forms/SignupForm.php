<?php

namespace app\modules\user\forms;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
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
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();
 
            if ($user->save()) {
                Yii::$app->mailer->compose('@app/modules/user/mails/emailConfirm', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setReplyTo(Yii::$app->params['adminEmail'])
                    ->setTo($this->email)
                    ->setSubject(Module::t('user', 'USER_SIGN_UP_MAIL_SUBJECT') . Yii::$app->name)
                    ->send();
                return $user;
            }
        }
 
        return null;
    }
}
