<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\region\Module;
use app\components\grid\LinkColumn;
use app\modules\region\models\Region;
use app\components\grid\SetColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\crop\models\search\CropSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('region', 'TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crop-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('region', 'CREATE'), ['create', 'view' => 'index'], ['class' => 'btn btn-success']) ?>
    </p>
    
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
                'label' => Module::t('region', 'REGION_WAREHOUSES'),
                'value' => function ($model) {return $model->warehousesNames;},
                'format' => 'html',
            ],
            'sort',
            [
                'class' => SetColumn::className(),
                'filter' => Region::getStatusArray(),
                'attribute' => 'status',
                'name' => 'StatusName',
                'cssClasses' => [
                    Region::STATUS_ACTIVE => 'success',
                    Region::STATUS_DISABLED => 'warning',
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}<hr>{warehouses}&nbsp;&nbsp;{delete}<hr>{change}',
                'contentOptions' => ['style' => 'width: 80px; font-size: 20px; text-align: center;'],
                'buttons' => [
                    'change' => function ($url, $model, $key) {
                        return $model->status == Region::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-danger btn-block',
                                'data' => [
                                    'confirm' => Module::t('region', 'DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('region', 'DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-success btn-block',
                                'data' => [
                                    'method' => 'post',
                                ],
                                'title' => Module::t('region', 'ENABLE'),
                            ]);
                    },
                    'warehouses' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-home"></span>', ['warehouses', 'id' => $model->id, 'view' => 'index'], [
                                'title' => Module::t('region', 'REGION_WAREHOUSES'),
                            ]);
                    },
                ],
                'header' => 'Действия',
            ],
        ],
    ]); ?>
</div>
