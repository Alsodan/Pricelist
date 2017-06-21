<?php

use yii\helpers\Html;
use app\modules\crop\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\crop\models\Crop */

$this->title = Module::t('crop', 'CROP_UPDATE') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('crop', 'CROPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('crop', 'CROP_UPDATE');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
