<?php

namespace app\modules\user\forms;
 
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;
 
class ProfileUpdateForm extends Model
{
    public $email;
 
    /**
     * @var User
     */
    private $_user;
 
    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->email = $user->email;
        parent::__construct($config);
    }
 
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => Module::t('user', 'USER_SIGN_UP_NOT_UNIQUE_EMAIL'),
                'filter' => ['<>', 'id', $this->_user->id],
            ],
            ['email', 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Module::t('user', 'USER_EMAIL'),
        ];
    }
 
    /**
     * Update User model
     * 
     * @return mixed|boolean
     */
    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->email = $this->email;
            return $user->save();
        } else {
            return false;
        }
    }
}