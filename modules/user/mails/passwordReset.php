<?php

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    
    <p>Здравствуйте, <?= Html::encode($user->username) ?>!</p>

    <p>Для изменения пароля пройдите по ссылке:</p>

    <?= Html::a(Html::encode($resetLink), $resetLink) ?>

    <p>Если Вы не регистрировались на нашем сайте, то просто удалите это письмо.</p>
    
</div>