<?php

namespace app\modules\user\models\backend\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\backend\User;
use app\modules\user\Module;
use app\modules\user\models\common\Profile;

/**
 * UserSearch represents the model behind the search form about `app\modules\user\models\backend\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $email;
    public $status;
    public $dateFrom;
    public $dateTo;
    
    public $profileName = '';
    public $profilePhone = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email', 'profileName', 'profilePhone'], 'safe'],
            [['dateFrom', 'dateTo'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function attributeLabels() {
        return [
            'created_at' => Module::t('user', 'USER_CREATED'),
            'updated_at' => Module::t('user', 'USER_UPDATED'),
            'username' => Module::t('user', 'USER_USERNAME'),
            'email' => Module::t('user', 'USER_EMAIL'),
            'status' => Module::t('user', 'USER_STATUS'),
            'dateFrom' => Module::t('user', 'USER_DATE_FROM'),
            'dateTo' => Module::t('user', 'USER_DATE_TO'),
            'profileName' => Module::t('user', 'USER_NAME'),
            'profilePhone' => Module::t('user', 'USER_PHONE'),
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        $dataProvider->sort->attributes['profileName'] = [
            'asc' => [Profile::tableName() . '.name' => SORT_ASC],
            'desc' => [Profile::tableName() . '.name' => SORT_DESC],
            'default' => SORT_ASC
        ];
        
        $dataProvider->sort->attributes['profilePhone'] = [
            'asc' => [Profile::tableName() . '.phone' => SORT_ASC],
            'desc' => [Profile::tableName() . '.phone' => SORT_DESC],
            'default' => SORT_ASC
        ];
        
        $this->load($params);

        if (!$this->validate()) {
            $query->joinWith('profile');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->dateFrom ? strtotime($this->dateFrom . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->dateTo ? strtotime($this->dateTo . ' 23:59:59') : null]);

        $query->joinWith(['profile' => function ($q) {
            $q->where(['like', Profile::tableName() . '.name', $this->profileName]);
            $q->andWhere(['like', Profile::tableName() . '.phone', $this->profilePhone]);
        }]);

        return $dataProvider;
    }
}
