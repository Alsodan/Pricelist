<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\crop\Module;

/* @var $this yii\web\View */
/* @var $warehouse app\modules\crop\models\Crop */

$this->title = $crop->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('crop', 'CROPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('crop', 'CROP') ?>: <?= $crop->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('crop', 'CROP_UPDATE'), ['update', 'id' => $crop->id], ['class' => 'btn btn-primary']) ?>
        </div>

        <hr>
        
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $crop,
                    'attributes' => [
                        'title',
                    ],
                ]) ?>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= DetailView::widget([
                    'model' => $crop,
                    'attributes' => [
                        'sort',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="panel-footer">
            <div class="row">
                <p class="pull-right">
                </p>
            </div>
        </div>
    </div>
</div>
