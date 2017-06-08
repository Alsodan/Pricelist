<?php

use yii\helpers\Html;
use app\modules\group\Module;
use kartik\sortinput\SortableInput;
use app\components\widgets\LinkedItemsWidget;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP_MANAGMENT') . ': ' . $group->title;
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/group/default/product-change', 'id' => $group->id]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { products: $("input[name=\'group-products\']").val() })
    });
    $(".list-group-item").click(function (event) {
        event.preventDefault();
        $("#users a.list-group-item.active").removeClass("active");
        $(this).addClass("active");
    });
');
?>
<div class="group-manage">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $group->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $group->id], ['id' => 'btn-info', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['id' => 'btn-update', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $group->id], ['id' => 'btn-users', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $group->id], ['id' => 'btn-warehouses', 'class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['products', 'id' => $group->id], ['id' => 'btn-products', 'class' => 'btn btn-primary active']) ?>
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
                        <p><?= Module::t('group', 'MANAGE_PRODUCTS_HINT {from} {to}', [
                            'from' => Module::t('group', 'ALL_PRODUCTS'),
                            'to' => Module::t('group', 'GROUP_PRODUCTS'),
                        ]) ?></p>
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <h5 class="text-center"><b>Склады</b></h5>
                        <?= LinkedItemsWidget::widget([
                            'links' => $warehouses,
                            'options' => ['id' => 'users'],
                            'selectedKey' => key($warehouses),
                            'linkRoute' => ['products', 'id' => $group->id, 'wh' => 'key'],
                        ]) ?>
                    </div>
                    
                    <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-4 col-sm-offset-1">
                        <h5 class="text-center"><b><?= Module::t('group', 'GROUP_PRODUCTS') ?></b></h5>
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
