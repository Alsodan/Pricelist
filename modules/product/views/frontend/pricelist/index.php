<?php

use yii\helpers\Html;
use app\modules\product\Module;
use kartik\grid\GridView;

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
');

?>
<div class="prices-index">
    
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
        </div>

        <?php
            $gridColumns = [
                [
                    'attribute' => 'title',
                    'label' => Module::t('product', 'PRODUCTS_TITLE'),
                    'width' => '100px',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'format' => 'html',
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
                                    \yii\helpers\Url::to(['/product/pricelist/product-prices-change', 'id' => $model[$arrkey]->id])//  Yii::$app->urlManager->createUrl(['/product/pricelist/product-prices-change', 'id' => $model[$arrkey]->id])
                                    ] 
                                ],
                                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                                'options' => [
                                    'pluginOptions' => [
                                        'initval' => $model[$arrkey]->price_with_tax,
                                        'min' => 0,
                                        'max' => 100000,
                                        'step' => 0.01,
                                        'decimals' => 2,
                                        'prefix' => '<span class="glyphicon glyphicon-rub"></span>',
                                        'verticalbuttons' => true
                                    ],
                                    'id' => 'inp_price_with_tax_' . $arrkey . '_' . $key,
                                    'readonly' => $model[$arrkey]->call_with_tax || $model[$arrkey]->noneed_with_tax,
                                ],
                                'afterInput'=>function () use ($model, $key, $arrkey) {
                                    return '<div class="row">'
                                            . '<div class="col-lg-6 col-md-6 col-sm-6">'
                                            . Html::checkbox('call_with_tax', $model[$arrkey]->call_with_tax, ['id' => 'cb_call_with_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'with_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'CALL_FOR_PRICE')])
                                            . '</div><div class="col-lg-6 col-md-6 col-sm-6">'
                                            . Html::checkbox('noneed_with_tax', $model[$arrkey]->noneed_with_tax, ['id' => 'cb_noneed_with_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'with_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'NOT_BUY')])
                                            . '</div></div><hr><div class="row"><div class="col-lg-12 col-md-12 col-sm-12">'
                                            . Module::t('product', 'PRODUCT_PRICE_NO_TAX')
                                            . kartik\touchspin\TouchSpin::widget([
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
                                            ])
                                            . '</div></div><div class="row"><div class="col-lg-6 col-md-6 col-sm-6">'
                                            . Html::checkbox('call_no_tax', $model[$arrkey]->call_no_tax, ['id' => 'cb_call_no_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'no_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'CALL_FOR_PRICE')])
                                            . '</div><div class="col-lg-6 col-md-6 col-sm-6">'
                                            . Html::checkbox('noneed_no_tax', $model[$arrkey]->noneed_no_tax, ['id' => 'cb_noneed_no_tax_' . $arrkey . '_' . $key, 'data' => ['input' => 'no_tax_' . $arrkey . '_' . $key], 'label' => Module::t('product', 'NOT_BUY')])
                                            . '</div>'
                                            . '</div>';
                                },
                                'format'=>['decimal', 2],
                                'value' => $model[$arrkey]->price_with_tax,
                                'beforeInput' => Module::t('product', 'PRODUCT_PRICE_WITH_TAX'),
                                'name' => 'Price[price_with_tax]',
                            ];
                        },
                        'readonly' => function($model) use ($arrkey) {
                            return (empty($model[$arrkey]));
                        },
                        'value' => function ($model) use ($arrkey) { return empty($model[$arrkey]) ? '' : $model[$arrkey]->prices; },
                        'width' => '200px',
                        'hAlign' => 'center',
                        'vAlign' => 'middle',
                        'header' => str_replace(' ', '<br>', $title),
                        'format' => 'html'
                    ]
                ]);
            }
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'hover' => true,
                    'striped' => false,
                    'toolbar' => false,
                    'panel' => false,
                    'resizableColumns' => false,
                    'layout' => '{items}',
                    'headerRowOptions' => [
                        'style' => 'overflow: auto; word-break: break-all;'
                    ],
                    'columns' => $gridColumns,
                ]); ?>
            </div>
        </div>
    
        <div class="panel-footer">
        </div>
    </div>
    
</div>
