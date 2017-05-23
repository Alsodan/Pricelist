<?php

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

use yii\helpers\Html;
use app\modules\user\Module;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    
    <p><?= Module::t('user', 'HELLO {username}', ['username' => $user->username]) ?></p>

    <p><?= Module::t('user', 'FOLLOW_TO_RESET_PASSWORD') ?></p>

    <?= Html::a(Html::encode($resetLink), $resetLink) ?>

    <p><?= Module::t('user', 'IGNORE_IF_DO_NOT_REGISTER') ?></p>
    
</div>