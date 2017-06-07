<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\grid\ActionColumn;
use app\modules\user\models\backend\User;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use kartik\date\DatePicker;
use app\modules\group\Module;
use kartik\sortinput\SortableInput;
use app\components\widgets\LinkedItemsWidget;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\group\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('group', 'GROUP_WAREHOUSES_MANAGE');
$this->params['breadcrumbs'][] = ['label' => Module::t('group', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $group->title, 'url' => ['view', 'id' => $group->id]];
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/admin/group/default/warehouse-change', 'id' => $group->id]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { warehouses: $("input[name=\'group-warehouses\']").val() })
    });
');
?>
<div class="group-warehouses">

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
                    <p><?= Module::t('group', 'MANAGE_WAREHOUSES_HINT {from} {to}', [
                        'from' => Module::t('group', 'ALL_WAREHOUSES'),
                        'to' => Module::t('group', 'GROUP_WAREHOUSES'),
                    ]) ?></p>
                </p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-5">
                <h5 class="text-center"><b><?= Module::t('group', 'GROUP_WAREHOUSES') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'group-warehouses',
                    'items' => $groupWarehouses,
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
                    <h5 class="text-center"><b><?= Module::t('group', 'ALL_WAREHOUSES') ?></b></h5>
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
            <?= Html::a(Module::t('group', 'BUTTON_BACK'), [$view, 'id' => $group->id], ['class' => 'btn btn-lg btn-primary']) ?>
        </div>
    </div>
</div>