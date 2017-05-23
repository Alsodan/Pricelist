<?php

use yii\helpers\Html;
use app\modules\admin\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = Module::t('admin', 'ADMIN_USERS_CREATE_USER_TITLE');
$this->params['breadcrumbs'][] = ['label' => Module::t('admin', 'ADMIN_TITLE'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('admin', 'ADMIN_USERS_INDEX_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
