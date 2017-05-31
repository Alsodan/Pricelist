<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\main\Module;

/* @var $this yii\web\View */
/* @var $group app\modules\main\models\Group */

$this->title = $group->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('main', 'GROUP') ?></b></h3>
        </div>
        <div class="panel-body">
            <?= Html::a(Module::t('main', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Module::t('main', 'GROUP_USERS_MANAGE'), ['user', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
            <?= $group->active ?
                Html::a(Module::t('main', 'GROUP_DISABLE'), ['change', 'id' => $group->id, 'view' => 'view'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Module::t('main', 'GROUP_DISABLE_CONFIRM'),
                    'method' => 'post',
                ],
            ]) :
            Html::a(Module::t('main', 'GROUP_ENABLE'), ['change', 'id' => $group->id, 'view' => 'view'], [
                'class' => 'btn btn-success',
                'data' => [
                    'method' => 'post',
                ],
            ])
            ?>
        </div>

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
                            'attribute' => 'active',
                            'value' => function ($model) { return $model->activityName; }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><b><?= Module::t('main', 'USERS') ?></b></h4>
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
                    <h4><b><?= Module::t('main', 'CROPS') ?></b></h4>
                </div>
                <?= DetailView::widget([
                    'model' => $group,
                    'attributes' => [
                        [
                            'attribute' => 'active',
                            'value' => function ($model) { return $model->activityName; }
                        ],
                    ],
                ]) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="text-center">
                    <h4><b><?= Module::t('main', 'WAREHOUSES') ?></b></h4>
                </div>
                <?= DetailView::widget([
                    'model' => $group,
                    'attributes' => [
                        [
                            'attribute' => 'active',
                            'value' => function ($model) { return $model->activityName; }
                        ],
                    ],
                ]) ?>
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
