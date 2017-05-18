<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\PasswordResetForm */
/* @var $dontShowForm boolean */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\Alert;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-password-reset">
    
    <?= Alert::widget() ?>
    
    <?= $dontShowForm ? '<!--' : '' ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please choose your new password:</p>
    <?= $dontShowForm ? '-->' : '' ?>
    
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'password-reset-form']); ?>

                <?= !$dontShowForm ? $form->field($model, 'password')->passwordInput(['autofocus' => true]) : '' ?>

                <div class="form-group">
                    <?= !$dontShowForm ? Html::submitButton('Save', ['class' => 'btn btn-primary']) : '' ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>