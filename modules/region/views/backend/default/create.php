<?php

use yii\helpers\Html;
use app\modules\region\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\region\models\Region */

$this->title = Module::t('region', 'CREATE');
$this->params['breadcrumbs'][] = ['label' => Module::t('region', 'TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
