<?php

use yii\helpers\Html;
use app\modules\product\Module;
use kartik\grid\GridView;

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\product\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('product', 'PRODUCTS_TITLE');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    $("input:checkbox").change(function (e) {
        var targetInput = "input#" + "inp_price_" + $(this).data("input");
        var targetCBCall = "input#" + "cb_call_" + $(this).data("input");
        var targetCBNoNeed = "input#" + "cb_noneed_" + $(this).data("input");
        
        if (~e.target.id.indexOf("cb_call")) {
            $(targetCBNoNeed).prop("checked", false);
        }
        else {
            $(targetCBCall).prop("checked", false);
        };
        
        $(targetInput).prop("readonly", $(targetCBNoNeed).is(":checked") || $(targetCBCall).is(":checked"));
    });
    $("table.kv-grid-table").stickyTableHeaders({fixedOffset: $("nav.navbar")});
    $("table.kv-grid-table tr").on("click", function() {
        $("table.kv-grid-table tr.hovered").removeClass("hovered")
        $(this).addClass("hovered");
    });
');

?>
<div class="prices-index">
    
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b><?= Module::t('product', 'PRODUCTS_TITLE') ?>: <?= Module::t('product', 'PRODUCT_PRICES_MANAGE') ?></b></h3>
            </div>
            <div class="panel-body text-center">
                <div class="alert alert-info">
                    <p class="col-lg-1 col-md-1 col-sm-2">
                        <span class="glyphicon glyphicon-info-sign" style="font-size: 48px;"></span>
                    </p>
                    <p class="col-lg-11 col-md-11 col-sm-10">
                        <p><br><?= Module::t('product', 'MANAGE_PRODUCTS_PRICES_HINT') ?><br></p>
                    </p>
                </div>
                <div class="row">
                    <?php $form = ActiveForm::begin([
                        'id' => 'pricelist-filter-form',
                        'method' => 'post',
                    ]); ?>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <label for="warehouse" class="control-label"><?= Module::t('product', 'WAREHOUSES') ?></label>
                        <?= Html::dropDownList('warehouse', $selectedWarehouse, \yii\helpers\ArrayHelper::map($warehouses, 'id', 'title'), ['prompt' => Module::t('product', 'ALL'), 'class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <label for="product" class="control-label"><?= Module::t('product', 'PRODUCT') ?></label>
                        <?= Html::dropDownList('product', $selectedProduct, \yii\helpers\ArrayHelper::map($products, 'id', 'title'), ['prompt' => Module::t('product', 'ALL'), 'class' => 'form-control']) ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <label for="pricelist-filter-submit-button" class="control-label">&nbsp;</label>
                        <?= Html::submitButton(Module::t('product', 'BUTTON_FILTER'), ['class' => 'btn btn-primary btn-block', 'name' => 'pricelist-filter-submit-button']) ?>
                    </div>
    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div style="margin: 0px 0px">
        <?php
            $gridColumns = [
                [
                    'attribute' => 'title',
                    'label' => Module::t('product', 'PRODUCTS_TITLE'),
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
                //$id = $value['id'];
                $title = $value['title'];
                $gridColumns = array_merge($gridColumns, [
                    [
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions'=> function ($model, $key, $index) use ($arrkey) {
                            return [
                                'header' => Module::t('product', 'PRODUCTS_PRICES_MANAGE'),
                                'size' => 'md',
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
                                        \yii\helpers\Url::to(['/product/pricelist/product-prices-change', 'id' => $model[$arrkey]->id])
                                    ] 
                                ],
                                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                                'options' => [
                                    'pluginOptions' => [
                                        'initval' => $model[$arrkey]->price_no_tax,
                                        'min' => 0,
                                        'max' => 100000,
                                        'step' => 0.01,
                                        'decimals' => 2,
                                        //'prefix' => '<span class="glyphicon glyphicon-rub"></span>',
                                        'verticalbuttons' => true
                                    ],
                                    'id' => 'inp_price_no_tax_' . $arrkey . '_' . $key,
                                    'readonly' => $model[$arrkey]->call_no_tax || $model[$arrkey]->noneed_no_tax,
                                ],
                                'afterInput'=>function () use ($model, $key, $arrkey) {
                                    return '<div class="row">'
                                            . '<div class="col-lg-12 col-md-12 col-sm-12">'
                                            //. Html::checkbox('call_with_tax', $model[$arrkey]->call_with_tax, ['id' => 'cb_call_with_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'with_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'CALL_FOR_PRICE')])
                                            //. '</div><div class="col-lg-6 col-md-6 col-sm-6">'
                                            //. Html::checkbox('noneed_with_tax', $model[$arrkey]->noneed_with_tax, ['id' => 'cb_noneed_with_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'with_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'NOT_BUY')])
                                            //. '</div></div><hr><div class="row"><div class="col-lg-12 col-md-12 col-sm-12">'
                                            //. Module::t('product', 'PRODUCT_PRICE_NO_TAX')
                                            /*. kartik\touchspin\TouchSpin::widget([
                                                'name' => 'Price[price_no_tax]', 
                                                'pluginOptions' => [
                                                    'initval' => $model[$arrkey]->price_no_tax,
                                                    'min' => 0,
                                                    'max' => 100000,
                                                    'step' => 0.01,
                                                    'decimals' => 2,
                                                    'prefix' => '<span class="glyphicon glyphicon-rub"></span>',
                                                    'verticalbuttons' => true
                                                ],
                                                'options' => [
                                                    'id' => 'inp_price_no_tax_' . $arrkey . '_' . $key,
                                                    'readonly' => $model[$arrkey]->call_no_tax || $model[$arrkey]->noneed_no_tax,
                                                ]
                                            ])*/
                                            //. '</div></div><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">'
                                            . Html::checkbox('call_no_tax', $model[$arrkey]->call_no_tax, ['id' => 'cb_call_no_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'no_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'CALL_FOR_PRICE')])
                                            . '</div><div class="col-lg-12 col-md-12 col-sm-12">'
                                            . Html::checkbox('noneed_no_tax', $model[$arrkey]->noneed_no_tax, ['id' => 'cb_noneed_no_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'no_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'NOT_BUY')])
                                            . '</div>'
                                            . '</div>';
                                },
                                'format'=>['decimal', 2],
                                'value' => $model[$arrkey]->price_no_tax,
                                'beforeInput' => Module::t('product', 'PRODUCT_PRICE_NO_TAX'),
                                'name' => 'Price[price_no_tax]',
                            ];
                        },
                        'readonly' => function($model) use ($arrkey) {
                            return (empty($model[$arrkey]));
                        },
                        'value' => function ($model) use ($arrkey) { return empty($model[$arrkey]) ? '' : $model[$arrkey]->getPricesNoTax(false, ''); },
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
                    //'containerOptions' => ['class' => 'kv-grid-container']
                ]); ?>
            </div>
        </div>
    </div>
    
</div>
