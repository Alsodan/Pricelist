<?php

namespace app\api\modules\v1\models;

use app\modules\user\models\common\User as BaseUser;
use app\api\modules\v1\models\queries\UserQuery;
use app\api\modules\v1\models\Profile;
use app\api\modules\v1\models\Price;
use app\modules\product\models\PriceUsers;

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
class User extends BaseUser implements \app\interfaces\SiteDataInterface
{
    //Склады менеджера
    public $warehouses;
    //Товары менеджера
    public $products;

    /*
     * Возвращаемые поля в REST API
     */
    public function fields() {
        return [
            'email' => function () {return $this->profileEmail;},
            'name' => function () {return $this->profileName;},
            'phone' => function () {return $this->profilePhone;},
            'warehouses',
            'products'
        ];
    }

    /**
     * Реализация интерфейса данных для сайта
     * @return array|[]
     */
    public static function getBaseData($data = [])
    {
        $pricesIds = \yii\helpers\ArrayHelper::getColumn($data['prices'], 'id');
        $users = User::findByPrices($pricesIds);
        return $users;
    }
    
    /*
     * Поиск с параметрами для REST API
     */
    public static function findByPrices($prices)
    {
        return static::find()
            ->with('profile')
            ->with('prices')
            ->with('prices.warehouse')
            ->with('prices.product')
            ->with('prices.product.crop')
            ->active()
            ->price($prices)
            ->orderBy([
                static::tableName() . '.sort' => SORT_ASC,
                static::tableName() . '.username' => SORT_ASC,
            ])
            ->all();
    }

    /**
     * Профиль менеджера
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
    
    /**
     * Имя менеджера из профиля
     */
    public function getProfileName()
    {
        return $this->profile->name;
    }
    
    /**
     * Рабочий телефон из профиля
     */
    public function getProfilePhone()
    {
        return str_replace(' ', '&nbsp;', $this->profile->phone);
    }
    
    /**
     * Email из профиля
     */
    public function getProfileEmail()
    {
        return str_replace(' ', '&nbsp;', $this->profile->work_email);
    }
    
    /**
     * Цены менеджера
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['id' => 'price_id'])
            ->viaTable(PriceUsers::tableName(), ['user_id' => 'id']);
    }
    
    /*
     * Заполняем массивы складов и товаров, за которые отвечает этот менеджер
     */
    public function checkWarehousesAndProducts($warehousesIds, $productsIds)
    {
        $this->warehouses = [];
        $this->products = [];
        
        foreach ($this->prices as $value) {
            if (in_array($value->warehouse_id, $warehousesIds) && in_array($value->product_id, $productsIds)) {
                $this->warehouses[] = $value->warehouse->title;
                $this->products[] = $value->product->crop->title;
            }
        }
        
        $this->warehouses = array_unique($this->warehouses);
        $this->products = array_unique($this->products);
        
        array_multisort($this->warehouses);
        array_multisort($this->products);
        
        $this->warehouses = implode(', ', $this->warehouses);
        $this->products = implode(', ', $this->products);
    }

    /**
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
