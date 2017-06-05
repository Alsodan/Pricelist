<?php

use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\forms\SignupForm */

use yii\helpers\Html;
use app\components\widgets\Alert;

$this->title = Module::t('user', 'USER_EMAIL_CONFIRM');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-email-confirm">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= Alert::widget() ?>

</div>