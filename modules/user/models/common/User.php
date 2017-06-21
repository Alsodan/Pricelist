<?php

namespace app\modules\user\models\common;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\modules\user\Module;
use app\modules\user\models\common\query\UserQuery;
use app\modules\group\models\Group;
use app\modules\group\models\GroupUsers;
use app\modules\product\models\Price;
use app\modules\product\models\PriceUsers;
use app\modules\product\models\Product;
use app\modules\warehouse\models\Warehouse;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $email
 * @property integer $status
 * @property string $role
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    //User statuses
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => Module::t('user', 'USER_SIGN_UP_NOT_UNIQUE_USERNAME')],
            ['username', 'string', 'min' => 2, 'max' => 255],
 
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Module::t('user', 'USER_SIGN_UP_NOT_UNIQUE_EMAIL')],
            ['email', 'string', 'max' => 255],
 
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            
            ['role', 'string', 'max' => 64],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('user', 'USER_ID'),
            'username' => Module::t('user', 'USER_USERNAME'),
            'email' => Module::t('user', 'USER_EMAIL'),
            'status' => Module::t('user', 'USER_STATUS'),
            'created_at' => Module::t('user', 'USER_CREATED'),
            'updated_at' => Module::t('user', 'USER_UPDATED'),
            'role' => Module::t('user', 'USER_ROLE'),
            
            'profileName' => Module::t('user', 'USER_NAME'),
            'profilePhone' => Module::t('user', 'USER_PHONE'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * @return UserQuery
     */
    public static function find()
    {
        return Yii::createObject(UserQuery::className(), [get_called_class()]);
    }
    
    /**
     * Get User status name
     * 
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
 
    /**
     * Get User statuses names array
     * 
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => Module::t('user', 'USER_STATUS_BLOCKED'),
            self::STATUS_ACTIVE => Module::t('user', 'USER_STATUS_ACTIVE'),
            self::STATUS_WAIT => Module::t('user', 'USER_STATUS_WAIT'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
 
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
 
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
 
    /**
     * Generate Authentication key before inserting model into DB
     * 
     * @param mixed $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @param integer $timeout
     * @return static|null
     */
    public static function findByPasswordResetToken($token, $timeout)
    {
        if (!static::isPasswordResetTokenValid($token, $timeout)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
 
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @param integer $timeout
     * @return bool
     */
    public static function isPasswordResetTokenValid($token, $timeout)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $timeout >= time();
    }
 
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
 
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Find user by email confirm token
     * 
     * @param string $email_confirm_token Email confirmation token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }
 
    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }
 
    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
    
    /**
     * Get User Profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
    
    /**
     * Get User Profile Name
     */
    public function getProfileName()
    {
        return $this->profile->name;
    }
    
    /**
     * Get User Profile Phone
     */
    public function getProfilePhone()
    {
        return $this->profile->phone;
    }
    
    /**
     * Get Profile Name and Phone
     * @return string
     */
    public function getProfileData()
    {
        return $this->profile->name . ' (' . $this->profile->phone . ')';
    }
    
    /**
     * Get Groups
     * 
     * @return array app/modules/group/models/Group[]
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(GroupUsers::tableName(), ['user_id' => 'id']);
    }
    
    /**
     * Get User active Prices
     */
    public function getActivePrices()
    {
        return $this->hasMany(Price::className(), ['id' => 'price_id'])
            ->viaTable(PriceUsers::tableName(), ['user_id' => 'id'])
            ->joinWith('product')
            ->joinWith('warehouse')
            ->where([Product::tableName() . '.status' => Product::STATUS_ACTIVE, Warehouse::tableName() . '.status' => Warehouse::STATUS_ACTIVE]);
    }
    
    /**
     * Get User active Products And Warehouses
     */
    public function getActiveProductsAndWarehouses()
    {
        $result = [];
        foreach ($this->activePrices as $price) {
            $result['product'][$price->product->id] = $price->product;
            $result['warehouse'][$price->warehouse->id] = $price->warehouse;
        }
        return $result;
    }
    
    /**
     * Block User
     */
    public function block()
    {
        $this->status = User::STATUS_BLOCKED;
        return $this->save(false);
    }

    /**
     * Unblock User
     */
    public function unblock()
    {
        $this->status = User::STATUS_ACTIVE;
        return $this->save(false);
    }
}
