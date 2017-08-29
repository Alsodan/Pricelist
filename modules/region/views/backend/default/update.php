<?php

use yii\helpers\Html;
use app\modules\region\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\region\models\Region */

$this->title = Module::t('region', 'UPDATE') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('region', 'TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('region', 'UPDATE');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
