<?php

use yii\helpers\Html;
use app\modules\product\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\Product */

$this->title = Module::t('product', 'PRODUCT_UPDATE') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('product', 'PRODUCTS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('product', 'PRODUCT_UPDATE');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
