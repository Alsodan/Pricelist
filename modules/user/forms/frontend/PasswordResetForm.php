<?php

namespace app\modules\user\forms\frontend;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use app\modules\user\models\common\User;
use app\modules\user\Module;

/**
 * Password reset form
 */
class PasswordResetForm extends Model
{
    public $password;
    
    /**
     * @var app\modules\user\models\User
     */
    private $_user;

    public function attributeLabels()
    {
        return [
            'password' => Module::t('user', 'USER_PASSWORD'),
        ];
    }
    
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param integer $timeout
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $timeout, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Module::t('user', 'EXEPTION_NO_PASSWORD_RESET_TOKEN'));
        }
        $this->_user = User::findByPasswordResetToken($token, $timeout);
        if (!$this->_user) {
            throw new InvalidParamException(Module::t('user', 'EXEPTION_WRONG_PASSWORD_RESET_TOKEN'));
        }
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    
    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
