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
            <h3 class="panel-title"><b><?= Module::t('warehouse', 'WAREHOUSE') ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a(Module::t('warehouse', 'WAREHOUSE_UPDATE'), ['update', 'id' => $warehouse->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Module::t('warehouse', 'WAREHOUSE_USERS_MANAGE'), ['users', 'id' => $warehouse->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Module::t('warehouse', 'WAREHOUSE_CROPS_MANAGE'), ['crops', 'id' => $warehouse->id], ['class' => 'btn btn-primary']) ?>
                <?= $warehouse->status == Warehouse::STATUS_ACTIVE ?
                    Html::a(Module::t('warehouse', 'WAREHOUSE_DISABLE'), ['block', 'id' => $warehouse->id, 'view' => 'view'], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Module::t('warehouse', 'WAREHOUSE_DISABLE_CONFIRM'),
                        'method' => 'post',
                    ],
                ]) :
                Html::a(Module::t('warehouse', 'WAREHOUSE_ENABLE'), ['unblock', 'id' => $warehouse->id, 'view' => 'view'], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post',
                    ],
                ])
                ?>
            </div>
        </div>

        <div class="row">
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
                    ],
                ]) ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><b><?= Module::t('warehouse', 'USERS') ?></b></h4>
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
                    <h4><b><?= Module::t('warehouse', 'CROPS') ?></b></h4>
                </div>
                <?= DetailView::widget([
                    'model' => $warehouse,
                    'attributes' => [
                        [
                            'attribute' => 'status',
                            'value' => function ($model) { return $model->statusName; }
                        ],
                    ],
                ]) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><b><?= Module::t('warehouse', 'GROUPS') ?></b></h4>
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
        </div>
    
        <div class="panel-footer">
            <div class="row">
                <p class="pull-right">
                </p>
            </div>
        </div>
    </div>
</div>
