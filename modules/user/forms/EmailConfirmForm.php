<?php

namespace app\modules\user\forms;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;
 
class EmailConfirmForm extends Model
{
    /**
     * @var User
     */
    private $_user;
 
    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Module::t('user', 'EXEPTION_NO_EMAIL_CONFIRM_TOKEN'));
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Module::t('user', 'EXEPTION_WRONG_EMAIL_CONFIRM_TOKEN'));
        }
        parent::__construct($config);
    }
 
    /**
     * Confirm email.
     *
     * @return boolean if email was confirmed.
     */
    public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();
 
        return $user->save();
    }
}
