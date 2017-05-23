<?php

use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\forms\PasswordResetForm */
/* @var $dontShowForm boolean */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\Alert;

$this->title = Module::t('user', 'USER_PASSWORD_RESET_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-password-reset">
    
    <?php if (Yii::$app->session->hasFlash('success')): ?>

        <?= Alert::widget() ?>

    <?php else: ?>

        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Module::t('user', 'USER_PASSWORD_RESET_SUBTITLE') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'password-reset-form']); ?>

                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Module::t('user', 'BUTTON_SAVE'), ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    
    <?php endif; ?>
</div>