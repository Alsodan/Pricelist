<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\organization\Module;
use app\modules\organization\models\Organization;
use app\api\modules\v1\models\Warehouse;

/* @var $this yii\web\View */
/* @var $model app\modules\crop\models\Crop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('organization', 'ORGANIZATION') ?>: <?= $this->title ?></b></h3>
        </div>
        <br>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="row">
                <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    
                    <label class="control-label" for="organization-datafile"><?= Module::t('organization', 'ORGANIZATION_FILE') ?></label>
                    
                    <?php if ($model->file) echo '<br>' . Html::a(Module::t('organization', 'VIEW_FILE'), \Yii::getAlias('@web/site') . $model->file, ['target' => '_blank']) ?>
                    
                    <?= $form->field($model, 'dataFile')->fileInput()->label('') ?>
                    
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'latitude')->textInput() ?>
                    
                    <?= $form->field($model, 'longitude')->textInput() ?>
                    
                    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>
                    
                    <?= $model->scenario == Organization::SCENARIO_ADMIN_EDIT ? $form->field($model, 'warehouse_id')->dropDownList(Warehouse::getWarehousesDropdown()) : '' ?>
                    
                    <?= $model->scenario == Organization::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Organization::getStatusArray()) : '' ?>
                    
                    <?= $form->field($model, 'sort') ?>

                </div>
            </div>
            <br>
            <div class="panel-footer">
                <?= Html::submitButton(Module::t('organization', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

