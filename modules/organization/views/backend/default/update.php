<?php

use yii\helpers\Html;
use app\modules\organization\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\crop\models\Crop */

$this->title = Module::t('organization', 'UPDATE') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('organization', 'TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('organization', 'UPDATE');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
