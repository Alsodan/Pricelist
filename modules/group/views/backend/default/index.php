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
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'class' => LinkColumn::className(),
                'defaultAction' => 'update',
                'icon' => 'glyphicon-pencil',
                'params' => ['view' => 'index'],
            ],
            [
                'label' => Module::t('group', 'GROUP_PRODUCTS_DIRECTORS_MANAGE'),
                'value' => function ($model) { 
                    $result = implode('<br>', $model->activeDirectorsNames);
                    return $result == '' ? 'Не назначены' : $result; },
                'format' => 'html',
            ],
            [
                'value' => function ($model) { return implode('<hr>', $model->activeUsersNames); },
                'format' => 'html',
                'label' => Module::t('group', 'USERS')
            ],
            [
                'value' => function ($model) { return implode('<br>', $model->activeWarehousesTitles); },
                'format' => 'html',
                'label' => Module::t('group', 'WAREHOUSES')
            ],
            [
                'value' => function ($model) { return implode('<br>', $model->activeGroupProductsTitles); },
                'format' => 'html',
                'label' => Module::t('group', 'PRODUCTS')
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}<hr>{change}<hr>{warehouses}&nbsp;&nbsp;{group-products}&nbsp;&nbsp;{products}<hr>{users}&nbsp;&nbsp;{managers}&nbsp;&nbsp;{directors}',
                'buttons' => [
                    'change' => function ($url, $model, $key) {
                        return $model->status == Group::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-danger btn-block',
                                'data' => [
                                    'confirm' => Module::t('group', 'GROUP_DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('group', 'GROUP_DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-success btn-block',
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
                    'group-products' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-leaf"></span>', ['group-products', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'GROUP_PRODUCTS')]);
                    },
                    'products' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-gift"></span>', ['products', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'PRODUCTS')]);
                    },
                    'warehouses' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-home"></span>', ['warehouses', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'WAREHOUSES')]);
                    },
                    'managers' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-tags"></span>', ['products-users', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE')]);
                    },
                    'directors' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-bullhorn"></span>', ['directors', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('group', 'GROUP_PRODUCTS_DIRECTORS_MANAGE')]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 100px; max-width: 100px; font-size: 20px; text-align: center;'],
                'header' => 'Действия',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
