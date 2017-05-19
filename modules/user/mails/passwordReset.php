<?php

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    
    <p><?= Yii::t('app', 'HELLO {username}', ['username' => $user->username]) ?></p>

    <p><?= Yii::t('app', 'FOLLOW_TO_RESET_PASSWORD') ?></p>

    <?= Html::a(Html::encode($resetLink), $resetLink) ?>

    <p><?= Yii::t('app', 'IGNORE_IF_DO_NOT_REGISTER') ?></p>
    
</div>