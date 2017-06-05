<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\forms\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\widgets\Alert;
use app\modules\user\Module;

$this->title = Module::t('user', 'USER_PASSWORD_RESET_REQUEST_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-password-reset-request">
    
    <?php if (Yii::$app->session->hasFlash('success') || Yii::$app->session->hasFlash('error')): ?>

        <?= Alert::widget() ?>

    <?php else: ?>
    
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Module::t('user', 'USER_PASSWORD_RESET_REQUEST_SUBTITLE') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'password-reset-request-form']); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Module::t('user', 'BUTTON_SEND'), ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    
    <?php endif; ?>
</div>