<?php

namespace app\modules\user\forms\frontend;
 
use yii\base\Model;
use Yii;
use app\modules\user\models\common\User;
use app\modules\user\Module;
 
/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;
 
    /**
     * @var User
     */
    private $_user;
 
    /**
     * @param User $user
     * @param array $config
     */
    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }
 
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['currentPassword', 'currentPassword'],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }
 
    public function attributeLabels()
    {
        return [
            'newPassword' => Module::t('user', 'USER_NEW_PASSWORD'),
            'newPasswordRepeat' => Module::t('user', 'USER_REPEAT_PASSWORD'),
            'currentPassword' => Module::t('user', 'USER_CURRENT_PASSWORD'),
        ];
    }
 
    /**
     * @param string $attribute
     * @param array $params
     */
    public function currentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'USER_PASSWORD_CHANGE_ERROR_WRONG_CURRENT_PASSWORD'));
            }
        }
    }
 
    /**
     * @return boolean
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->newPassword);
            return $user->save();
        } else {
            return false;
        }
    }
}