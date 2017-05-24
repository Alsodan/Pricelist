<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\grid\ActionColumn;
use app\modules\user\models\backend\User;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use kartik\date\DatePicker;
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('user', 'ADMIN_USERS_INDEX_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('user', 'LINK_CREATE_USER'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'username',
                'class' => LinkColumn::className(),
            ],
            'email:email',
            [
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ]),
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            [
                'class' => SetColumn::className(),
                'filter' => User::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssClasses' => [
                    User::STATUS_ACTIVE => 'success',
                    User::STATUS_WAIT => 'warning',
                    User::STATUS_BLOCKED => 'default',
                ]
            ],

            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>
