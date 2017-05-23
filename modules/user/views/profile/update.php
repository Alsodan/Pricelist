<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\user\Module;
 
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */
 
$this->title = Module::t('user', 'USER_PROFILE_UPDATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'USER_PROFILE_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-update">
 
    <h1><?= Html::encode($this->title) ?></h1>
 
    <div class="user-form">
 
        <?php $form = ActiveForm::begin(); ?>
 
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
 
        <div class="form-group">
            <?= Html::submitButton(Module::t('user', 'BUTTON_SAVE'), ['class' => 'btn btn-primary']) ?>
        </div>
 
        <?php ActiveForm::end(); ?>
 
    </div>
 
</div>