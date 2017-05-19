<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'USER_LOG_IN_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'USER_LOG_IN_SUBTITLE') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    <?= Yii::t('app', 'USER_LOG_IN_HINT_RESET') ?><?= Html::a(Yii::t('app', 'LINK_RESET_IT'), ['password-reset-request']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'BUTTON_LOGIN'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>