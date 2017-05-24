<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Module::t('user', 'ADMIN_USERS_INDEX_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('user', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('user', 'BUTTON_DELETE'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('user', 'ADMIN_USERS_DELETE_USER_CONFIRM'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'status',
                'value' => $model->statusName
            ]
        ],
    ]) ?>

</div>
