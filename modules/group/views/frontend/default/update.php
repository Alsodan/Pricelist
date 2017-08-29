<?php

use yii\helpers\Html;
use app\modules\group\Module;
use yii\widgets\ActiveForm;
use app\modules\group\models\Group;
use app\components\widgets\Alert;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP') . ': ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-manage">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b><?= Module::t('group', 'GROUP_MANAGMENT') ?>: <?= $model->title ?></b></h3>
        </div>
        <div class="panel-body text-center">
            <div class="btn-group" role="group">
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width active']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-leaf"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['group-products', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'PRODUCTS_MANAGE'), ['products', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-tag"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'), ['products-users', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-sunglasses"></span><br>' . Module::t('group', 'GROUP_LOG'), ['log', 'id' => $model->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class=" col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <?= Alert::widget() ?>
                <?php else: ?>
            
                    <?php $form = ActiveForm::begin([
                        'id' => 'edit-form',
                        'options' => ['data-pjax' => true],
                        ]); ?>

                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                        <?= $model->scenario == Group::SCENARIO_ADMIN_EDIT ? $form->field($model, 'status')->dropDownList(Group::getStatusArray()) : '' ?>

                        <div class="form-group">
                            <?= Html::submitButton(Module::t('group', 'BUTTON_SAVE'), ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
            
                <?php endif; ?>
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
