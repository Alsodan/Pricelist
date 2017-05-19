<?php

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

use yii\helpers\Html;
 
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/email-confirm', 'token' => $user->email_confirm_token]);
?>
 
<div class="email-confirm">
    
    <p><?= Yii::t('app', 'HELLO {username}', ['username' => $user->username]) ?></p>

    <p><?= Yii::t('app', 'FOLLOW_TO_CONFIRM_EMAIL') ?></p>

    <?= Html::a(Html::encode($confirmLink), $confirmLink) ?>

    <p><?= Yii::t('app', 'IGNORE_IF_DO_NOT_REGISTER') ?></p>

</div>