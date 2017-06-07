<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\group\Module;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\search\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('group', 'GROUPS_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('group', 'GROUP_CREATE'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'class' => LinkColumn::className(),
            ],
            [
                'class' => SetColumn::className(),
                'filter' => Group::getStatusArray(),
                'attribute' => 'status',
                'name' => 'StatusName',
                'cssClasses' => [
                    Group::STATUS_ACTIVE => 'success',
                    Group::STATUS_DISABLED => 'warning',
                ]
            ],
            [
                'value' => function ($model) { return implode('<br>', $model->profilesAsStringArray); },
                'format' => 'html',
                'label' => Module::t('group', 'USERS')
            ],
            [
                'value' => function ($model) { return implode('<br>', $model->warehousesAsStringArray); },
                'format' => 'html',
                'label' => Module::t('group', 'WAREHOUSES')
            ],
            [
                'value' => function ($model) { return implode('<br>', $model->productsAsStringArray); },
                'format' => 'html',
                'label' => Module::t('group', 'PRODUCTS')
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{users}&nbsp;&nbsp;{warehouses}&nbsp;&nbsp;{products}&nbsp;&nbsp;{change}',
                'buttons' => [
                    'change' => function ($url, $model, $key) {
                        return $model->status == Group::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-danger',
                                'data' => [
                                    'confirm' => Module::t('group', 'GROUP_DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('group', 'GROUP_DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-success',
                                'data' => [
                                    'method' => 'post',
                                ],
                                'title' => Module::t('group', 'GROUP_ENABLE'),
                            ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'GROUP_UPDATE')]);
                    },
                    'users' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-user"></span>', ['users', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'USERS')]);
                    },
                    'products' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-gift"></span>', ['products', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'PRODUCTS')]);
                    },
                    'warehouses' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-home"></span>', ['warehouses', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'WAREHOUSES')]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 152px;']
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
