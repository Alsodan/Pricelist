<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\product\Module;
use app\modules\crop\models\Crop;
use app\modules\product\models\Product;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\Product */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="group-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('product', 'PRODUCT') ?>: <?= $this->title ?></b></h3>
        </div>
        <br>
        <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                    <?= $form->field($model, 'crop_id')->widget(Select2::className(), [
                        'data' => Crop::getCropsDropdown(),
                        'options' => ['placeholder' => Module::t('product', 'PRODUCT_SELECT_FROM_LIST_HINT')],
                    ]) ?>

                    <?= $form->field($model, 'grade')->input('text') ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'specification')->textarea() ?>
                    
                    <?= $model->scenario == Product::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Product::getStatusArray()) : '' ?>
                    
                    <?= $form->field($model, 'sort') ?>
                    
                </div>
            </div>
            <br>
            <div class="panel-footer">
                <?= Html::submitButton(Module::t('product', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
