<?php
 
use yii\helpers\Html;
use app\modules\site\Module;
use app\components\widgets\Alert;
/* @var $this yii\web\View */
 
$this->title = Module::t('site', 'PAGE_EDIT');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs_only_last'] = true;
?>
<div class="container-fluid">
    <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <?= Alert::widget() ?>
        <?php endif; ?>

        <div class="col-lg-2 col-md-2 col-sm-3">
            <?= $model->menuWidget(true, 'mainmenu', false) ?>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php if ($model->submenu): ?>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <?= $model->menuWidget(false, 'menu' . $model->sub['id']) ?>
                        </div>
                    <?php endif; ?>
                    <?= Html::tag('div', $this->render('pageForm', ['model' => $model]), ['class' => 'col-lg-9 col-md-9 col-sm-9']) ?>
                </div>
            </div>
        </div>
    </div>
</div>