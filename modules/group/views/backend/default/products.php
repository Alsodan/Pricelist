<?php
use yii\helpers\Html;
use app\modules\group\Module;
use kartik\sortinput\SortableInput;
use app\components\widgets\LinkedItemsWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\group\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('group', 'PRODUCTS_MANAGE');
$this->params['breadcrumbs'][] = ['label' => Module::t('group', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $group->title, 'url' => ['view', 'id' => $group->id]];
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/admin/group/default/product-change', 'id' => $group->id, 'wh' => $selectedWarehouse]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { products: $("input[name=\'group-products\']").val() })
    });
');
?>
<div class="user-index">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= $this->title ?>: <?= $group->title ?></b></h3>
        </div>
        <div class="panel-body">
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
            <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <h5 class="text-center"><b><?= Module::t('group', 'WAREHOUSES') ?></b></h5>
                        <?= LinkedItemsWidget::widget([
                            'links' => $warehouses,
                            'options' => ['id' => 'users'],
                            'selectedKey' => $selectedWarehouse,
                            'linkRoute' => ['products', 'id' => $group->id, 'wh' => 'key'],
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
        </div>
        <div class="panel-footer">
            <?= Html::a(Module::t('group', 'BUTTON_BACK'), [$view, 'id' => $group->id], ['class' => 'btn btn-lg btn-primary']) ?>
        </div>
    </div>
</div>