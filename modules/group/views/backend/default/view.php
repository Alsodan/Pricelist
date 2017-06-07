<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\group\Module;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = $group->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('group', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $this->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['products', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                <?= $group->status == Group::STATUS_ACTIVE ?
                    Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('group', 'GROUP_DISABLE'), ['block', 'id' => $group->id, 'view' => 'view'], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Module::t('group', 'GROUP_DISABLE_CONFIRM'),
                        'method' => 'post',
                    ],
                ]) :
                Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('group', 'GROUP_ENABLE'), ['unblock', 'id' => $group->id, 'view' => 'view'], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post',
                    ],
                ])
                ?>
            </div>
        </div>

        <hr>
        
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $group,
                    'attributes' => [
                        'title',
                    ],
                ]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $group,
                    'attributes' => [
                        [
                            'attribute' => 'status',
                            'value' => function ($model) { return $model->statusName; }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<b><?= Module::t('group', 'USERS') ?></b></h4>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $users,
                    'showHeader' => false,
                    'layout' => "{items}",
                    'columns' => [
                        [
                            'value' => function ($model) { return $model->name . ' (' . $model->phone . ')'; }
                        ]
                    ]
                ])
                ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;<b><?= Module::t('group', 'WAREHOUSES') ?></b></h4>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $warehouses,
                    'showHeader' => false,
                    'layout' => "{items}",
                    'columns' => [
                        [
                            'value' => function ($model) { return $model->title; }
                        ]
                    ]
                ])
                ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;<b><?= Module::t('group', 'PRODUCTS') ?></b></h4>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $products,
                    'showHeader' => false,
                    'layout' => "{items}",
                    'columns' => [
                        [
                            'value' => function ($model) { return $model->title . ($model->subtitle ? ' (' . $model->subtitle . ')' : ''); }
                        ]
                    ]
                ])
                ?>
            </div>         
        </div>
    
        <div class="panel-footer">
            <div class="row">
                <p class="pull-right">
                </p>
            </div>
        </div>
    </div>
</div>
