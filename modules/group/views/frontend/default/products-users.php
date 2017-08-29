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
    
    $("table.kv-grid-table").stickyTableHeaders({fixedOffset: $("nav.navbar")});
    $("table.kv-grid-table tr").on("click", function() {
        $("table.kv-grid-table tr.hovered").removeClass("hovered")
        $(this).addClass("hovered");
    });
');
?>
<div class="group-manage">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $group->title ?></b></h3>
            </div>
            <div class="panel-body text-center">
                <div class="btn-group" role="group">
                    <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-leaf"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['group-products', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'PRODUCTS_MANAGE'), ['products', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-tag"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'), ['products-users', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width active']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-sunglasses"></span><br>' . Module::t('group', 'GROUP_LOG'), ['log', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                </div>
                <hr>
                <div class="alert alert-info">
                    <p class="col-lg-1 col-md-1 col-sm-2">
                        <span class="glyphicon glyphicon-info-sign" style="font-size: 48px;"></span>
                    </p>
                    <p class="col-lg-11 col-md-11 col-sm-10">
                        <p><br><?= Module::t('group', 'MANAGE_PRODUCTS_USERS_HINT') ?><br></p>
                    </p>
                </div>
            </div>
        </div>
    </div>

        <?php
            $gridColumns = [
                [
                    'attribute' => 'title',
                    'label' => Module::t('group', 'PRODUCTS'),
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'format' => 'html',
                    'contentOptions' => [
                        'class' => 'first-column',
                        'height' => '75px;',
                        'style' => 'min-width: 160px; max-width: 160px;',
                        'width' => '160px',
                    ],
                    'headerOptions' => [
                        'style' => 'min-width: 160px; max-width: 160px;',
                        'width' => '160px',
                    ],
                ]
            ];

            foreach ($columns as $arrkey => $value) {
                $id = $value['id'];
                $title = $value['title'];
                $gridColumns = array_merge($gridColumns, [
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions'=> function ($model) use ($arrkey, $group) {
                            return [
                                'header' => Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'),
                                'size'=>'md',
                                'asPopover' => false,
                                'buttonsTemplate' => '{submit}',
                                'submitButton' => [
                                    'class' => 'btn btn-sm btn-primary',
                                    'icon' => '<i class="glyphicon glyphicon-ok"></i>'
                                ],
                                'inlineSettings' => [
                                    'templateBefore' => '<div class="panel-body">',
                                    'templateAfter' => \kartik\editable\Editable::INLINE_AFTER_1,
                                    'closeButton' => '<button type="button" class="btn btn-sm btn-default kv-editable-close" title="Отмена"><i class="glyphicon glyphicon-remove"></i></button>',
                                ],
                                'formOptions' => [
                                    'action' => [
                                        Yii::$app->urlManager->createUrl(['/group/default/product-users-change', 'id' => $model[$arrkey]->id])
                                    ] 
                                ],
                                'inputType' => \kartik\editable\Editable::INPUT_SELECT2,
                                'options' => [
                                    'pluginOptions' => [
                                        'multiple' => true,
                                        'placeholder' => Module::t('group', 'SELECT_USERS'),
                                    ],
                                    'data' => $group->activeUsersNames,
                                ],
                                'name' => 'users',
                                'value' => $model[$arrkey]->usersList,
                                'size' => 'md',
                                'beforeInput' => Module::t('group', 'SELECT_USERS_FULL'),
                            ];
                        },
                        'readonly' => function($model) use ($arrkey) {
                            return (empty($model[$arrkey]));
                        },
                        'value' => function ($model) use ($arrkey) { return empty($model[$arrkey]) ? '' : (empty($model[$arrkey]->activeUsersNames) ? Module::t('group', 'NO_USERS') : implode(', ', $model[$arrkey]->activeUsersNames)); },
                        'hAlign' => 'center',
                        'vAlign' => 'middle',
                        'header' => str_replace(' ', '<br>', $title),
                        'format' => 'html',
                        'contentOptions' => [
                            'style' => 'min-width: 160px; max-width: 160px;',
                            'height' => '74px;'
                        ],
                    ]
                ]);
            }
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'hover' => true,
                    'striped' => true,
                    'toolbar' => false,
                    'panel' => false,
                    'resizableColumns' => false,
                    'responsive' => false,
                    'layout' => '{items}',
                    'headerRowOptions' => [
                        'style' => 'overflow: auto; word-break: break-all; background-color: #fdb813;',
                    ],
                    'columns' => $gridColumns,
                ]); ?>
            </div>
        </div>

</div>
