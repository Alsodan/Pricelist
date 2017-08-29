<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\grid\ActionColumn;
use app\modules\user\models\backend\User;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use kartik\date\DatePicker;
use app\modules\region\Module;
use kartik\sortinput\SortableInput;
use app\components\widgets\LinkedItemsWidget;
use app\modules\warehouse\models\Warehouse;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\warehouse\models\WarehouseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('region', 'REGION_WAREHOUSES_MANAGE');
$this->params['breadcrumbs'][] = ['label' => Module::t('region', 'REGIONS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $region->title, 'url' => ['view', 'id' => $region->id]];
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/admin/region/default/warehouse-change', 'id' => $region->id]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { warehouses: $("input[name=\'region-warehouses\']").val() })
    });
');
?>
<div class="warehouse-users">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= $this->title ?>: <?= $region->title ?></b></h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                <p class="col-lg-1 col-md-1 col-sm-2">
                    <span class="glyphicon glyphicon-info-sign" style="font-size: 48px;"></span>
                </p>
                <p class="col-lg-11 col-md-11 col-sm-10">
                    <p><?= Module::t('region', 'MANAGE_WAREHOUSES_HINT {from} {to}', [
                        'from' => Module::t('region', 'ALL_WAREHOUSES'),
                        'to' => Module::t('region', 'REGION_WAREHOUSES'),
                    ]) ?></p>
                </p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-5">
                <h5 class="text-center"><b><?= Module::t('region', 'REGION_WAREHOUSES') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'region-warehouses',
                    'items' => $regionWarehouses,
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
                    <h5 class="text-center"><b><?= Module::t('region', 'ALL_WAREHOUSES') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'all-warehouses',
                    'items' => $allWarehouses,
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
            <?= Html::a(Module::t('region', 'BUTTON_BACK'), [$view, 'id' => $region->id], ['class' => 'btn btn-lg btn-primary']) ?>
        </div>
    </div>
</div>