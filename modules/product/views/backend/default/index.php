<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
    
<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'class' => LinkColumn::className(),
                'value' => function ($model) { return $model->title . ($model->subtitle ? ' (' . $model->subtitle . ')' : ''); },
            ],
            [
                'value' => function ($model) { return implode('<hr>', $model->linkedDataList);},
                'format' => 'html',
                'label' => Module::t('product', 'DATA_TITLE')
            ],
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
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{warehouses}&nbsp;&nbsp;{managers}&nbsp;&nbsp;{prices}&nbsp;&nbsp;{change}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'PRODUCT_UPDATE')]);
                    },
                    'change' => function ($url, $model, $key) {
                        return $model->status == Product::STATUS_ACTIVE ?
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['block', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-danger',
                                'data' => [
                                    'confirm' => Module::t('product', 'PRODUCT_DISABLE_CONFIRM'),
                                    'method' => 'post',
                                ],
                                'title' => Module::t('product', 'PRODUCT_DISABLE'),
                            ]) :
                            Html::a('<span class="glyphicon glyphicon-off"></span>', ['unblock', 'id' => $model->id, 'view' => 'index'], [
                                'class' => 'btn btn-xs btn-success',
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
                            Html::a('<span class="glyphicon glyphicon-rub"></span>', ['prices', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'PRICES')]);
                    },
                    'managers' => function ($url, $model, $key) {
                        return 
                            Html::a('<span class="glyphicon glyphicon-tags"></span>', ['products-users', 'id' => $model->id, 'view' => 'index'], ['title' => Module::t('product', 'USERS')]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 150px;']
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
