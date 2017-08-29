<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\warehouse\Module;
use app\modules\warehouse\models\Warehouse;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $model app\modules\group\models\Group */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('warehouse', 'WAREHOUSE') ?>: <?= $this->title ?></b></h3>
        </div>
        <br>
        <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $model->scenario == Group::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Warehouse::getStatusArray()) : '' ?>
                    
                    <?= $form->field($model, 'sort') ?>

                </div>
            </div>
            <br>
            <div class="panel-footer">
                <?= Html::submitButton(Module::t('warehouse', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
