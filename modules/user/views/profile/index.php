<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\user\Module;
 
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */
 
$this->title = Module::t('user', 'USER_PROFILE_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">
 
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('user', 'BUTTON_UPDATE'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('user', 'LINK_PASSWORD_CHANGE'), ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
        ],
    ]) ?>
 
</div>