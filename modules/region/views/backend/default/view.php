<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\region\Module;
use app\modules\region\models\Region;

/* @var $this yii\web\View */
/* @var $warehouse app\modules\crop\models\Crop */

$this->title = $region->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('region', 'TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('region', 'REGION') ?>: <?= $region->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('region', 'UPDATE'), ['update', 'id' => $region->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('region', 'REGION_WAREHOUSES'), ['warehouses', 'id' => $region->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= $region->status == Region::STATUS_ACTIVE ?
                        Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('region', 'DISABLE'), ['block', 'id' => $region->id, 'view' => 'view'], [
                        'class' => 'btn btn-danger btn-medium-width',
                        'data' => [
                            'confirm' => Module::t('region', 'DISABLE_CONFIRM'),
                            'method' => 'post',
                        ],
                    ]) :
                    Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('region', 'ENABLE'), ['unblock', 'id' => $region->id, 'view' => 'view'], [
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
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $region,
                    'attributes' => [
                        'title',
                        'sort',
                    ],
                ]) ?>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $region,
                    'attributes' => [
                        [
                            'label' => Module::t('region', 'REGION_WAREHOUSES'),
                            'value' => function ($model) {return $model->warehousesNames;},
                            'format' => 'html'
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
