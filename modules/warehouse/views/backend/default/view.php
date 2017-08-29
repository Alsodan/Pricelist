<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\warehouse\Module;
use app\modules\warehouse\models\Warehouse;

/* @var $this yii\web\View */
/* @var $warehouse app\modules\warehouse\models\Warehouse */

$this->title = $warehouse->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('warehouse', 'WAREHOUSES_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('warehouse', 'WAREHOUSE') ?>: <?= $this->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('warehouse', 'WAREHOUSE_UPDATE'), ['update', 'id' => $warehouse->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span><br>' . Module::t('warehouse', 'WAREHOUSE_GROUPS_MANAGE'), ['groups', 'id' => $warehouse->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('warehouse', 'WAREHOUSE_PRODUCTS_MANAGE'), ['products', 'id' => $warehouse->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= $warehouse->status == Warehouse::STATUS_ACTIVE ?
                    Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('warehouse', 'WAREHOUSE_DISABLE'), ['block', 'id' => $warehouse->id, 'view' => 'view'], [
                    'class' => 'btn btn-danger btn-medium-width',
                    'data' => [
                        'confirm' => Module::t('warehouse', 'WAREHOUSE_DISABLE_CONFIRM'),
                        'method' => 'post',
                    ],
                ]) :
                Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('warehouse', 'WAREHOUSE_ENABLE'), ['unblock', 'id' => $warehouse->id, 'view' => 'view'], [
                    'class' => 'btn btn-success btn-medium-width',
                    'data' => [
                        'method' => 'post',
                    ],
                ])
                ?>
            </div>
        </div>

        <hr>
        
        <div class="row">
            <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <?= DetailView::widget([
                        'model' => $warehouse,
                        'attributes' => [
                            'title',
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <?= DetailView::widget([
                        'model' => $warehouse,
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'value' => function ($model) { return $model->statusName; }
                            ],
                            'sort'
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="text-center">
                        <h4><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<b><?= Module::t('warehouse', 'GROUPS') ?></b></h4>
                    </div>
                    <?= GridView::widget([
                        'dataProvider' => $groups,
                        'showHeader' => false,
                        'layout' => "{items}",
                        'columns' => [
                            'title',
                        ]
                    ])
                    ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="text-center">
                        <h4><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;<b><?= Module::t('warehouse', 'PRODUCTS') ?></b></h4>
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
        </div>
        <div class="panel-footer">
            <div class="row">
                <p class="pull-right">
                </p>
            </div>
        </div>
    </div>
</div>
