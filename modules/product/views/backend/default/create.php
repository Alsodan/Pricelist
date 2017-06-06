<?php

use yii\helpers\Html;
use app\modules\product\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\Product */

$this->title = Module::t('product', 'PRODUCT_CREATE');
$this->params['breadcrumbs'][] = ['label' => Module::t('product', 'PRODUCTS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
