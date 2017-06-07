<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\grid\ActionColumn;
use app\modules\user\models\backend\User;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use kartik\date\DatePicker;
use app\modules\warehouse\Module;
use kartik\sortinput\SortableInput;
use app\components\widgets\LinkedItemsWidget;
use app\modules\warehouse\models\Warehouse;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\warehouse\models\WarehouseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('warehouse', 'WAREHOUSE_USERS_MANAGE');
$this->params['breadcrumbs'][] = ['label' => Module::t('warehouse', 'WAREHOUSES_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $warehouse->title, 'url' => ['view', 'id' => $warehouse->id]];
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/admin/warehouse/default/user-change', 'id' => $warehouse->id]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { users: $("input[name=\'warehouse-users\']").val() })
    });
');
?>
<div class="warehouse-users">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= $this->title ?>: <?= $warehouse->title ?></b></h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <p class="col-lg-1 col-md-1 col-sm-2">
                    <span class="glyphicon glyphicon-info-sign" style="font-size: 48px;"></span>
                </p>
                <p class="col-lg-11 col-md-11 col-sm-10">
                    <p><?= Module::t('warehouse', 'MANAGE_USERS_HINT {from} {to}', [
                        'from' => Module::t('warehouse', 'ALL_USERS'),
                        'to' => Module::t('warehouse', 'WAREHOUSE_USERS'),
                    ]) ?></p>
                </p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-5">
                <h5 class="text-center"><b><?= Module::t('warehouse', 'WAREHOUSE_USERS') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'warehouse-users',
                    'items' => $warehouseUsers,
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
                <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-5 col-sm-offset-2">
                    <h5 class="text-center"><b><?= Module::t('warehouse', 'ALL_USERS') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'all-users',
                    'items' => $allUsers,
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
        <div class="panel-footer">
            <?= Html::a(Module::t('warehouse', 'BUTTON_BACK'), [$view, 'id' => $warehouse->id], ['class' => 'btn btn-lg btn-primary']) ?>
        </div>
    </div>
</div>