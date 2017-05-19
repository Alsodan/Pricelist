<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\SignupForm */

use yii\helpers\Html;
use app\widgets\Alert;

$this->title = Yii::t('app', 'USER_EMAIL_CONFIRM');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-default-email-confirm">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= Alert::widget() ?>

</div>