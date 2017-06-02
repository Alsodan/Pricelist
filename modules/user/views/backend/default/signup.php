<?php

use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\forms\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\widgets\Alert;

$this->title = Module::t('user', 'ADMIN_USERS_CREATE_USER_TITLE');
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'ADMIN_USERS_INDEX_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    $("#same_email_label").click(function () {
        if ($("#same_email").prop("checked"))
        {
            $("#signupform-workemail").val($("#signupform-email").val());
            $("#form-signup").yiiActiveForm("validateAttribute", "signupform-workemail");
        }
        else
            $("#signupform-workemail").val("");
    });
');
    
?>

<div class="user-default-signup">
    
    <?php if (Yii::$app->session->hasFlash('success')): ?>

        <?= Alert::widget() ?>

    <?php else: ?>
    
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <hr>
                
                    <?= $form->field($model, 'name') ?>
                    
                    <?= $form->field($model, 'phone') ?>
                    
                    <?= $form->field($model, 'workEmail') ?>
                    
                    <?= Html::checkbox('same_email', false, ['label' => Module::t('user', 'USER_PROFILE_WORK_EMAIL_IS_SAME_AS_USER_EMAIL'), 'id' => 'same_email', 'labelOptions' => ['id' => 'same_email_label']]) ?>
                    
                    <hr>
                    
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'captchaAction' => '/user/default/captcha',
                        'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-5">{input}</div></div>',
                    ]) ?>
                    
                    <div class="form-group">
                        <?= Html::submitButton(Module::t('user', 'BUTTON_SIGNUP'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    
    <?php endif; ?>
</div>