<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\models\backend\User;
use app\modules\user\Module;
use app\modules\group\models\Group;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\backend\User */
/* @var $profile app\modules\user\models\common\Profile */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
    $("#same_email_label").click(function () {
        if ($("#same_email").prop("checked"))
        {
            $("#profile-work_email").val($("#user-email").val());
            $("#form-create-user").yiiActiveForm("validateAttribute", "profile-work_email");
        }
        else
            $("#profile-work_email").val("");
    });
');
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'form-create-user']); ?>

    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'newPassword')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($user, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($user, 'status')->dropDownList(User::getStatusesArray()) ?>
    
    <hr>
    
    <?= $form->field($profile, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profile, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profile, 'work_email')->textInput(['maxlength' => true]) ?>
    
    <?= Html::checkbox('same_email', false, ['label' => Module::t('user', 'USER_PROFILE_WORK_EMAIL_IS_SAME_AS_USER_EMAIL'), 'id' => 'same_email', 'labelOptions' => ['id' => 'same_email_label']]) ?>
    
    <hr>
    
    <?= $form->field($profile, 'groupsList')->widget(Select2::className(), [
        'data' => Group::getGroupsDropdown(),
        'options' => ['multiple' => true],
    ])->label(Module::t('user', 'USER_PROFILE_GROUP')) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('user', 'BUTTON_SAVE'), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
