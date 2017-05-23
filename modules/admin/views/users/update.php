<?php

use yii\helpers\Html;
use app\modules\admin\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = Module::t('admin', 'ADMIN_USERS_UPDATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Module::t('admin', 'ADMIN_TITLE'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('admin', 'ADMIN_USERS_INDEX_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('admin', 'LINK_UPDATE');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
