<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\product\Module;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use app\modules\product\models\Product;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\product\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('product', 'PRODUCTS_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('product', 'PRODUCT_CREATE'), ['create', 'view' => 'index'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'class' => LinkColumn::className(),
                'value' => function ($model) { return $model->title . ($model->subtitle ? ' (' . $model->subtitle . ')' : ''); },
                'defaultAction' => 'update',
                'icon' => 'glyphicon-pencil',
                'params' => ['view' => 'index'],
            ],
            [
                'value' => function ($model) { return implode('<hr>', $model->linkedDataList);},
                'format' => 'html',
                'label' => Module::t('product', 'DATA_TITLE')
            ],
            'sort',
            [
                'class' => SetColumn::className(),
                'filter' => Product::getStatusArray(),
                'attribute' => 'status',
                'name' => 'StatusName',
                'cssClasses' => [
                    Product::STATUS_ACTIVE => 'success',
                    Product::STATUS_DISABLED => 'warning',
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}<hr>{warehouses}&nbsp;&nbsp;{managers}&nbsp;&nbsp;{prices}<hr>{change}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'PRODUCT_UPDATE')]);
                    },
                    'change' => function ($url, $model, $key) {
                        return $model->status == Product::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-danger btn-block',
                                'data' => [
                                    'confirm' => Module::t('product', 'PRODUCT_DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('product', 'PRODUCT_DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-md btn-success btn-block',
                                'data' => [
                                    'method' => 'post',
                                ],
                                'title' => Module::t('product', 'PRODUCT_ENABLE'),
                            ]);
                    },
                    'warehouses' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-home"></span>', ['warehouses', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'WAREHOUSES')]);
                    },
                    'prices' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-rub"></span>', ['product-prices', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'PRICES')]);
                    },
                    'managers' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-tags"></span>', ['product-users', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'USERS')]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 100px; font-size: 20px; text-align: center;'],
                'header' => 'Действия',
            ],
        ],
    ]); ?>
</div>
