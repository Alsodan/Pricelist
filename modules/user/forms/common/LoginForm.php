<?php

namespace app\modules\user\forms\common;

use Yii;
use yii\base\Model;
use app\modules\user\models\common\User;
use app\modules\user\Module;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Module::t('user', 'USER_USERNAME'),
            'password' => Module::t('user', 'USER_PASSWORD'),
            'rememberMe' => Module::t('user', 'USER_REMEMBER_ME'),
        ];
    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Module::t('user', 'USER_LOG_IN_WRONG_LOGIN_OR_PASSWORD'));
            } elseif ($user && $user->status == User::STATUS_BLOCKED) {
                $this->addError('username', Module::t('user', 'USER_LOG_IN_ACCOUNT_BLOCKED'));
            } elseif ($user && $user->status == User::STATUS_WAIT) {
                $this->addError('username', Module::t('user', 'USER_LOG_IN_EMAIL_NOT_CONFIRMED'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * 
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
