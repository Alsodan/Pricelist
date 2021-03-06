<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\user\Module;
 
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\ChangePasswordForm */
 
$this->title = Module::t('user', 'USER_PASSWORD_CHANGE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'USER_PROFILE_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-password-change">
 
    <h1><?= Html::encode($this->title) ?></h1>
 
    <div class="user-form">
 
        <?php $form = ActiveForm::begin(); ?>
 
        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>
 
        <div class="form-group">
            <?= Html::submitButton(Module::t('user', 'BUTTON_SAVE'), ['class' => 'btn btn-primary']) ?>
        </div>
 
        <?php ActiveForm::end(); ?>
 
    </div>
 
</div>