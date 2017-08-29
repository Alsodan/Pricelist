<?php

use yii\helpers\Html;
use app\modules\group\Module;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP') . ': ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-manage">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP_MANAGMENT') ?>: <?= $model->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width active']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-leaf"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['group-products', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'PRODUCTS_MANAGE'), ['products', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-tag"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'), ['products-users', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-sunglasses"></span><br>' . Module::t('group', 'GROUP_LOG'), ['log', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="text-center">
                        <h5><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;<b><?= Module::t('group', 'WAREHOUSES') ?></b></h5>
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
                        <h5><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;<b><?= Module::t('group', 'PRODUCTS') ?></b></h5>
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
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="text-center">
                        <h5><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<b><?= Module::t('group', 'USERS') ?></b></h5>
                    </div>
                    <?= GridView::widget([
                        'dataProvider' => $users,
                        'showHeader' => false,
                        'layout' => "{items}",
                        'columns' => [
                            [
                                'value' => function ($model) { return $model->profile->name . ' (' . $model->profile->phone . ')'; }
                            ]
                        ]
                    ])
                    ?>
                </div>
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
