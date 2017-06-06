<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\product\Module;
use app\modules\crop\models\Crop;
use app\modules\product\models\Product;
use kartik\select2\Select2;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\Product */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
    $("input:checkbox").change(function () {
        var target = "input#" + $(this).data("input");
        $(target).attr("readonly", !$(target).attr("readonly"));
    });
');
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group_id')->widget(Select2::className(), [
        'data' => Group::getGroupsDropdown(),
        'options' => ['placeholder' => Module::t('product', 'PRODUCT_SELECT_FROM_LIST_HINT')],
    ]) ?>
    
    <?= $form->field($model, 'crop_id')->widget(Select2::className(), [
        'data' => Crop::getCropsDropdown(),
        'options' => ['placeholder' => Module::t('product', 'PRODUCT_SELECT_FROM_LIST_HINT')],
    ]) ?>
    
    <?= $form->field($model, 'grade')->input('text') ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'specification')->textarea() ?>
    
    <?php
        if ($model->call_no_tax) {
            echo $form->field($model, 'price_no_tax')->input('text', ['readonly' => true]);
        }
        else {
            echo $form->field($model, 'price_no_tax');
        }
    ?>
    
    <?= $form->field($model, 'call_no_tax')->checkbox(['data' => ['input' => 'product-price_no_tax']]) ?>
        
    <?php
        if ($model->call_with_tax) {
            echo $form->field($model, 'price_with_tax')->input('text', ['readonly' => true]);
        }
        else {
            echo $form->field($model, 'price_with_tax');
        }
    ?>
    
    <?= $form->field($model, 'call_with_tax')->checkbox(['data' => ['input' => 'product-price_with_tax']]) ?>
    
    <div class="form-group">
        <?= Html::submitButton(Module::t('product', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
