<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\group\Module;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $model app\modules\group\models\Group */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $this->title ?></b></h3>
        </div>
        <br>
        <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $model->scenario == Group::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Group::getStatusArray()) : '' ?>

                </div>
            </div>
            <div class="panel-footer">
                <?= Html::submitButton(Module::t('group', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
