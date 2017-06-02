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
                    'attribute' => 'dateFrom',
                    'attribute2' => 'dateTo',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ]),
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            'profileName',
            'profilePhone',
            [
                'attribute' => 'groups',
                'format' => 'html',
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

            [
                'class' => ActionColumn::className(),
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{change}',
                'buttons' => [
                    'change' => function ($url, $model, $key) {
                        return $model->status ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-danger',
                                'data' => [
                                    'confirm' => Module::t('user', 'USER_BLOCK_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('user', 'USER_BLOCK'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-success',
                                'data' => [
                                    'method' => 'post',
                                ],
                                'title' => Module::t('user', 'USER_UNBLOCK'),
                            ]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 90px;']
            ],
        ],
    ]); ?>
</div>
