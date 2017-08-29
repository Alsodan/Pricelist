<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\region\Module;
use app\modules\region\models\Region;
use app\api\modules\v1\models\Warehouse;

/* @var $this yii\web\View */
/* @var $model app\modules\crop\models\Crop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('region', 'REGION') ?>: <?= $this->title ?></b></h3>
        </div>
        <br>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="row">
                <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    
                    <?= $model->scenario == Region::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Region::getStatusArray()) : '' ?>
                    
                    <?= $form->field($model, 'sort') ?>

                </div>
            </div>
            <br>
            <div class="panel-footer">
                <?= Html::submitButton(Module::t('region', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

