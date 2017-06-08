<?php

use yii\helpers\Html;
use app\modules\group\Module;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP_MANAGMENT') . ': ' . $group->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-manage">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $group->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $group->id], ['id' => 'btn-info', 'class' => 'btn btn-primary active']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['id' => 'btn-update', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $group->id], ['id' => 'btn-users', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $group->id], ['id' => 'btn-warehouses', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['products', 'id' => $group->id], ['id' => 'btn-products', 'class' => 'btn btn-primary']) ?>
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
                                'value' => function ($model) { return $model->name . ' (' . $model->phone . ')'; }
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
