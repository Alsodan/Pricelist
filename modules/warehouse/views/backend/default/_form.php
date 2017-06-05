<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\warehouse\Module;
use app\modules\warehouse\models\Warehouse;
use kartik\select2\Select2;
use app\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $model app\modules\group\models\Group */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Warehouse::getStatusArray()) ?>

    <?= ''/*$form->field($model, 'groupsList')->widget(Select2::className(), [
        'data' => Group::getGroupsDropdown(),
        'options' => ['multiple' => true],
    ])->label(Module::t('warehouse', 'WAREHOUSE_GROUPS'))*/ ?>
    
    <div class="form-group">
        <?= Html::submitButton(Module::t('warehouse', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
