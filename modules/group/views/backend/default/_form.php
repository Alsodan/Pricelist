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
    
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $model->scenario == Group::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Group::getStatusArray()) : '' ?>

        <div class="form-group">
            <?= Html::submitButton(Module::t('group', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
