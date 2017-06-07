<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\product\Module;
use app\modules\product\models\Product;

/* @var $this yii\web\View */
/* @var $warehouse app\modules\product\models\Product */

$this->title = $product->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('product', 'PRODUCTS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('product', 'PRODUCT') ?>: <?= $product->title . ($product->subtitle ? ' (' . $product->subtitle . ')' : '') ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('product', 'PRODUCT_UPDATE'), ['update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('product', 'PRODUCT_USERS_MANAGE'), ['users', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('product', 'PRODUCT_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                <?= $product->status == Product::STATUS_ACTIVE ?
                    Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('product', 'PRODUCT_DISABLE'), ['block', 'id' => $product->id, 'view' => 'view'], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Module::t('product', 'PRODUCT_DISABLE_CONFIRM'),
                        'method' => 'post',
                    ],
                ]) :
                Html::a('<span class="glyphicon glyphicon-off"></span><br>' . Module::t('product', 'PRODUCT_ENABLE'), ['unblock', 'id' => $product->id, 'view' => 'view'], [
                    'class' => 'btn btn-success',
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
                    'model' => $product,
                    'attributes' => [
                        'title',
                        [
                            'attribute' => 'subtitle',
                            'label' => Module::t('product', 'PRODUCT_SUBTITLE_SHORT'),
                        ],
                        [
                            'attribute' => 'grade',
                            'label' => Module::t('product', 'PRODUCT_GRADE_SHORT'),
                        ],
                        'specification'
                    ],
                ]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $product,
                    'attributes' => [
                        [
                            'attribute' => 'status',
                            'value' => function ($model) { return $model->statusName; }
                        ],
                        [
                            'value' => function ($model) { return $model->crop->title; },
                            'label' => Module::t('product', 'CROP'),
                        ],
                                                [
                            'value' => function ($model) { return $model->group->title; },
                            'label' => Module::t('product', 'GROUP'),
                        ],
                        [
                            'attribute' => 'price_no_tax',
                            'value' => function ($model) { return $model->getPrice('price_no_tax'); },
                        ],
                        [
                            'attribute' => 'price_with_tax',
                            'value' => function ($model) { return $model->getPrice('price_with_tax'); },
                        ],
                    ],
                ]) ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="text-center">
                    <h4><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<b><?= Module::t('product', 'USERS') ?></b></h4>
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
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="text-center">
                    <h4><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;<b><?= Module::t('product', 'WAREHOUSES') ?></b></h4>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $warehouses,
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
