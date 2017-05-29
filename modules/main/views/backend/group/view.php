<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\main\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Group */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'GROUPS_TITLE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('main', 'GROUP_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= $model->active ?
            Html::a(Module::t('main', 'GROUP_DISABLE'), ['change', 'id' => $model->id, 'view' => 'view'], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('main', 'GROUP_DISABLE_CONFIRM'),
                'method' => 'post',
            ],
        ]) :
        Html::a(Module::t('main', 'GROUP_ENABLE'), ['change', 'id' => $model->id, 'view' => 'view'], [
            'class' => 'btn btn-success',
            'data' => [
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'active',
                'value' => function ($model) { return $model->activityName; }
            ],
        ],
    ]) ?>

</div>
