<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use app\modules\user\models\User;

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
            'password' => Yii::t('app', 'USER_PASSWORD'),
        ];
    }
    
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('app', 'EXEPTION_NO_PASSWORD_RESET_TOKEN'));
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('app', 'EXEPTION_WRONG_PASSWORD_RESET_TOKEN'));
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
