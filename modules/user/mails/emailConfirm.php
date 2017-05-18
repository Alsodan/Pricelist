<?php

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

use yii\helpers\Html;
 
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/email-confirm', 'token' => $user->email_confirm_token]);
?>
 
<div class="email-confirm">
    
    <p>Здравствуйте, <?= Html::encode($user->username) ?>!</p>

    <p>Для подтверждения адреса пройдите по ссылке:</p>

    <?= Html::a(Html::encode($confirmLink), $confirmLink) ?>

    <p>Если Вы не регистрировались на нашем сайте, то просто удалите это письмо.</p>

</div>