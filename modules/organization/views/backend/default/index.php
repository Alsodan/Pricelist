<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\organization\Module;
use app\components\grid\LinkColumn;
use app\modules\organization\models\Organization;
use app\components\grid\SetColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\crop\models\search\CropSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('organization', 'TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crop-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('organization', 'CREATE'), ['create', 'view' => 'index'], ['class' => 'btn btn-success']) ?>
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
            'address',
            'phone',
            [
                'attribute' => 'file',
                'value' => function ($model) {return Html::a(Module::t('organization', 'VIEW_FILE'), \Yii::getAlias('@web/site') . $model->file, ['target' => '_blank']);},
                'format' => 'raw'
            ],
            [
                'attribute' => 'warehouse_id',
                'value' => function ($model) {return $model->warehouse->title;},
            ],
            'sort',
            [
                'class' => SetColumn::className(),
                'filter' => Organization::getStatusArray(),
                'attribute' => 'status',
                'name' => 'StatusName',
                'cssClasses' => [
                    Organization::STATUS_ACTIVE => 'success',
                    Organization::STATUS_DISABLED => 'warning',
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}<hr>{change}',
                'contentOptions' => ['style' => 'width: 100px; font-size: 20px; text-align: center;'],
                'buttons' => [
                    'change' => function ($url, $model, $key) {
                        return $model->status == Organization::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-danger btn-block',
                                'data' => [
                                    'confirm' => Module::t('organization', 'DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('organization', 'DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-success btn-block',
                                'data' => [
                                    'method' => 'post',
                                ],
                                'title' => Module::t('organization', 'ENABLE'),
                            ]);
                    },
                ],
                'header' => 'Действия',
            ],
        ],
    ]); ?>
</div>
