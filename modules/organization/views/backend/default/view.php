<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\organization\Module;
use app\modules\organization\models\Organization;

/* @var $this yii\web\View */
/* @var $warehouse app\modules\crop\models\Crop */

$this->title = $organization->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('organization', 'TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('organization', 'ORGANIZATION') ?>: <?= $organization->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('organization', 'UPDATE'), ['update', 'id' => $organization->id], ['class' => 'btn btn-primary']) ?>
            <?= $organization->status == Organization::STATUS_ACTIVE ?
                    Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('organization', 'DISABLE'), ['block', 'id' => $organization->id, 'view' => 'view'], [
                    'class' => 'btn btn-danger btn-medium-width',
                    'data' => [
                        'confirm' => Module::t('organization', 'DISABLE_CONFIRM'),
                        'method' => 'post',
                    ],
                ]) :
                Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('organization', 'ENABLE'), ['unblock', 'id' => $organization->id, 'view' => 'view'], [
                    'class' => 'btn btn-success btn-medium-width',
                    'data' => [
                        'method' => 'post',
                    ],
                ])
            ?>
        </div>

        <hr>
        
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $organization,
                    'attributes' => [
                        'title',
                        'address',
                        'phone',
                        [
                            'attribute' => 'dataFile',
                            'value' => function ($model) {return Html::a(Module::t('organization', 'VIEW_FILE'), \Yii::getAlias('@web/site') . $model->file, ['target' => '_blank']);},
                            'format' => 'raw'
                        ],
                        [
                            'attribute' => 'warehouse_id',
                            'value' => function ($model) {return $model->warehouse->title;},
                        ],
                    ],
                ]) ?>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $organization,
                    'attributes' => [
                        'sort',
                        'latitude',
                        'longitude',
                        'info'
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
