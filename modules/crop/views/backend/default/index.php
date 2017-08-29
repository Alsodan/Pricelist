<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\crop\Module;
use app\components\grid\LinkColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\crop\models\search\CropSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('crop', 'CROPS_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crop-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('crop', 'CROP_CREATE'), ['create', 'view' => 'index'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'class' => LinkColumn::className(),
                'defaultAction' => 'update',
                'icon' => 'glyphicon-pencil',
                'params' => ['view' => 'index'],
            ],
            'sort',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}',
                'contentOptions' => ['style' => 'width: 80px; font-size: 20px; text-align: center;'],
                'header' => 'Действия',
            ],
        ],
    ]); ?>
</div>
