<?php

use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\forms\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\widgets\Alert;

$this->title = Module::t('user', 'USER_SIGN_UP_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-signup">
    
    <?php if (Yii::$app->session->hasFlash('success')): ?>

        <?= Alert::widget() ?>

    <?php else: ?>
    
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Module::t('user', 'USER_SIGN_UP_SUBTITLE') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'captchaAction' => '/user/default/captcha',
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Module::t('user', 'BUTTON_SIGNUP'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    
    <?php endif; ?>
</div>