<?php

use yii\helpers\Html;
use app\modules\group\Module;
use kartik\sortinput\SortableInput;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP_MANAGMENT') . ': ' . $group->title;
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/group/default/user-change', 'id' => $group->id]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { users: $("input[name=\'group-users\']").val() })
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
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $group->id], ['id' => 'btn-info', 'class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['id' => 'btn-update', 'class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $group->id], ['id' => 'btn-warehouses', 'class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['products', 'id' => $group->id], ['id' => 'btn-products', 'class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $group->id], ['id' => 'btn-users', 'class' => 'btn btn-primary btn-medium-width active']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-tag"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'), ['products-user', 'id' => $model->id], ['id' => 'btn-users', 'class' => 'btn btn-primary btn-medium-width']) ?>
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
                        <p><?= Module::t('group', 'MANAGE_USERS_HINT {from} {to}', [
                            'from' => Module::t('group', 'ALL_USERS'),
                            'to' => Module::t('group', 'GROUP_USERS'),
                        ]) ?></p>
                    </p>
                </div>
            </div>
        </div>

        <div class="row center-block">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'hover' => true,
                    'striped' => false,
                    'toolbar' => false,
                    'layout' => '{items}',
                    /*'panel' => [
                        'type' => GridView::TYPE_INFO,
                        'heading' => '<i class="glyphicon glyphicon-book"></i>&nbsp&nbsp&nbsp' . Yii::$app->name,
                        'footer' => false,
                    ],*/
                    'columns' => [
                        'id', 'title', 'subtitle', 'price_no_tax', 'price_with_tax'
                    ],
                ]); ?>
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
