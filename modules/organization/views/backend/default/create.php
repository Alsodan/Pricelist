<?php

use yii\helpers\Html;
use app\modules\organization\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\organization\models\Organization */

$this->title = Module::t('organization', 'CREATE');
$this->params['breadcrumbs'][] = ['label' => Module::t('organization', 'TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
