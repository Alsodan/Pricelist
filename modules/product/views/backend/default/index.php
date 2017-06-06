<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\warehouse\Module;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use app\modules\warehouse\models\Warehouse;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\search\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('warehouse', 'WAREHOUSES_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('warehouse', 'WAREHOUSE_CREATE'), ['create', 'view' => 'index'], ['class' => 'btn btn-success']) ?>
    </p>
    
<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'class' => LinkColumn::className(),
            ],
            [
                'class' => SetColumn::className(),
                'filter' => Warehouse::getStatusArray(),
                'attribute' => 'status',
                'name' => 'StatusName',
                'cssClasses' => [
                    Warehouse::STATUS_ACTIVE => 'success',
                    Warehouse::STATUS_DISABLED => 'warning',
                ]
            ],
            [
                'value' => function ($model) { return implode('<br>', $model->groupsAsStringArray);},
                'format' => 'html',
                'label' => Module::t('warehouse', 'GROUPS')
            ],            
            [
                'value' => function ($model) { return implode('<br>', $model->profilesAsStringArray);},
                'format' => 'html',
                'label' => Module::t('warehouse', 'USERS')
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{users}&nbsp;&nbsp;{crops}&nbsp;&nbsp;{change}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('warehouse', 'WAREHOUSE_UPDATE')]);
                    },
                    'change' => function ($url, $model, $key) {
                        return $model->status == Warehouse::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-danger',
                                'data' => [
                                    'confirm' => Module::t('warehouse', 'WAREHOUSE_DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('warehouse', 'WAREHOUSE_DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-success',
                                'data' => [
                                    'method' => 'post',
                                ],
                                'title' => Module::t('warehouse', 'WAREHOUSE_ENABLE'),
                            ]);
                    },
                    'users' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-user"></span>', ['users', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('warehouse', 'USERS')]);
                    },
                    'crops' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-leaf"></span>', ['crops', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('warehouse', 'CROPS')]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 130px;']
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
