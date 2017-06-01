<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */
/* @var $profile app\modules\user\models\common\Profile */

$this->title = $user->username;
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'ADMIN_USERS_INDEX_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('user', 'USER') ?></b></h3>
        </div>
        <div class="panel-body">
                   
            <?= Html::a(Module::t('user', 'BUTTON_UPDATE'), ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
            <?= $user->status ?
                    Html::a(Module::t('user', 'USER_BLOCK'), ['block', 'id' => $user->id, 'view' => 'view'], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Module::t('user', 'USER_BLOCK_CONFIRM'),
                            'method' => 'post',
                        ],
                    ]) :
                    Html::a(Module::t('user', 'USER_UNBLOCK'), ['unblock', 'id' => $user->id, 'view' => 'view'], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'method' => 'post',
                        ],
                    ]);
            ?>

        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $user,
                    'attributes' => [
                        'username',
                        'email:email',
                        [
                            'attribute' => 'status',
                            'value' => $user->statusName
                        ],
                        'groups'
                    ],
                ]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $profile,
                    'attributes' => [
                        'name',
                        'phone',
                        'work_email:email',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <p class="pull-right">
                    <b><?= Module::t('user', 'USER_CREATED') ?></b>:&nbsp;
                    <?= Yii::$app->formatter->asDatetime($user->created_at) ?>
                    &nbsp;&nbsp;&nbsp;
                    <b><?= Module::t('user', 'USER_UPDATED') ?></b>:&nbsp;
                    <?= Yii::$app->formatter->asDatetime($user->updated_at) ?>
                    &nbsp;&nbsp;&nbsp;
                </p>
            </div>
        </div>
    </div>
</div>
