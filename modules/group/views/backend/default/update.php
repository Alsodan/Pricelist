<?php

use yii\helpers\Html;
use app\modules\group\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Group */

$this->title = Module::t('group', 'GROUP_UPDATE') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('group', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('group', 'GROUP_UPDATE');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
