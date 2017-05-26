<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\models\backend\User;
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\backend\User */
/* @var $profile app\modules\user\models\common\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'newPassword')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($user, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($user, 'status')->dropDownList(User::getStatusesArray()) ?>
    
    <hr>
    
    <?= $form->field($profile, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profile, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profile, 'work_email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('user', 'BUTTON_SAVE'), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
