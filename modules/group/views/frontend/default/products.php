<?php

use yii\helpers\Html;
use app\modules\group\Module;
use kartik\sortinput\SortableInput;
use app\components\widgets\LinkedItemsWidget;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP_MANAGMENT') . ': ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/group/default/product-change', 'id' => $model->id, 'wh' => $selectedWarehouse]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { products: $("input[name=\'group-products\']").val() })
    });
');
?>
<div class="group-manage">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $model->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['products', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width active']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-tag"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'), ['products-users', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <div class="alert alert-info">
                    <p class="col-lg-1 col-md-1 col-sm-2">
                        <span class="glyphicon glyphicon-info-sign" style="font-size: 48px;"></span>
                    </p>
                    <p class="col-lg-11 col-md-11 col-sm-10">
                        <p><?= Module::t('group', 'MANAGE_WAREHOUSE_PRODUCTS_HINT {from} {to}', [
                            'from' => Module::t('group', 'ALL_PRODUCTS'),
                            'to' => Module::t('group', 'GROUP_PRODUCTS'),
                        ]) ?></p>
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <h5 class="text-center"><b><?= Module::t('group', 'WAREHOUSES') ?></b></h5>
                        <?= LinkedItemsWidget::widget([
                            'links' => $warehouses,
                            'options' => ['id' => 'users'],
                            'selectedKey' => $selectedWarehouse,
                            'linkRoute' => ['products', 'id' => $model->id, 'wh' => 'key'],
                        ]) ?>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <h5 class="text-center"><b><?= Module::t('group', 'GROUP_WAREHOUSE_PRODUCTS') ?>: <?= $warehouses[$selectedWarehouse] ?></b></h5>
                        <?= SortableInput::widget([
                            'name'=>'group-products',
                            'items' => $groupProducts,
                            'hideInput' => true,
                            'sortableOptions' => [
                                'connected' => true,
                                'itemOptions' => ['class'=>'alert alert-success'],
                                'options' => ['style' => 'min-height: 50px'],
                            ],
                            'options' => [
                                'class' => 'form-control siw', 
                                'readonly' => true,
                            ]
                        ]);?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <h5 class="text-center"><b><?= Module::t('group', 'ALL_PRODUCTS') ?></b></h5>
                        <?= SortableInput::widget([
                            'name'=>'all-products',
                            'items' => $allProducts,
                            'hideInput' => true,
                            'sortableOptions' => [
                                'itemOptions'=>['class'=>'alert alert-info'],
                                'connected'=>true,
                                'options' => ['style' => 'min-height: 50px'],
                            ],
                            'options' => [
                                'class'=>'form-control siw', 
                                'readonly'=>true,
                            ]
                        ]);?>
                    </div>
                </div>
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
