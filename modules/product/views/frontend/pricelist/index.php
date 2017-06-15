<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\product\Module;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use app\modules\product\models\Product;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\product\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('product', 'PRODUCTS_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">
    
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'hover' => true,
        'toolbar' => false,
        'panel' => [
            'type' => GridView::TYPE_INFO,
            'heading' => '<i class="glyphicon glyphicon-book"></i>&nbsp&nbsp&nbsp' . Yii::$app->name,
            'footer' => false,
        ],
        'columns' => [
            'id', 'title', 'subtitle', 'price_no_tax', 'price_with_tax'
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
