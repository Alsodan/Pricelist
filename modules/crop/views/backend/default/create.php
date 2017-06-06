<?php

use yii\helpers\Html;
use app\modules\crop\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\crop\models\Crop */

$this->title = Module::t('crop', 'CROP_CREATE');
$this->params['breadcrumbs'][] = ['label' => Module::t('crop', 'CROPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
