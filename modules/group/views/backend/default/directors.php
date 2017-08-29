<?php
use yii\helpers\Html;
use app\modules\group\Module;
use kartik\sortinput\SortableInput;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\group\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('group', 'GROUP_PRODUCTS_DIRECTORS_MANAGE');
$this->params['breadcrumbs'][] = ['label' => Module::t('group', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $group->title, 'url' => ['view', 'id' => $group->id]];
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/admin/group/default/director-change', 'id' => $group->id]);
$this->registerJs('
    $("input.siw").change(function () {
        $.post( "' . $ajaxUrl . '", { users: $("input[name=\'group-directors\']").val() })
    });
');
?>
<div class="group-users">

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
                    <p><?= Module::t('group', 'MANAGE_DIRECTORS_HINT {from} {to}', [
                        'from' => Module::t('group', 'ALL_DIRECTORS'),
                        'to' => Module::t('group', 'GROUP_DIRECTORS'),
                    ]) ?></p>
                </p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-5">
                <h5 class="text-center"><b><?= Module::t('group', 'GROUP_DIRECTORS') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'group-directors',
                    'items' => $groupDirectors,
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
                    <h5 class="text-center"><b><?= Module::t('group', 'ALL_DIRECTORS') ?></b></h5>
                <?= SortableInput::widget([
                    'name'=>'all-directors',
                    'items' => $allDirectors,
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