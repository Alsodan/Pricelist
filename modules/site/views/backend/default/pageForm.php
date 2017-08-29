<?php

/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\site\Module;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use vova07\imperavi\Widget;

$this->registerJs('
    $(\'[data-toggle="tooltip"]\').tooltip();
');
?>

    <?php $form = ActiveForm::begin(['id' => $model->page->id . '-update']); ?>

    <?= $form->field($model->page, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model->page, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model->page, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <hr>
    
    <?= $form->field($model->page, 'header')->textInput(['maxlength' => true])->label(Module::t('site', 'HEADER') . '<span class="glyphicon glyphicon-info-sign tooltip-info" data-toggle="tooltip" data-placement="right" title="Будет выведен сразу под верхним меню страницы"></span>') ?>
    
    <?= $form->field($model->page, 'subheader')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 100,
            'pastePlainText' => true,
            'imageManagerJson' => Url::to(['default/images-get']),
            'imageUpload' => Url::to(['default/image-upload']),
            'plugins' => [
                'imagemanager',
            ]
        ]
    ])->label(Module::t('site', 'SUBHEADER') . '<span class="glyphicon glyphicon-info-sign tooltip-info" data-toggle="tooltip" data-placement="right" title="Будет выведен сразу под заголовком. Можно использовать HTML тэги"></span>') ?>
    
    <?= $form->field($model->page, 'content')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 100,
            'pastePlainText' => true,
            'imageManagerJson' => Url::to(['default/images-get']),
            'imageUpload' => Url::to(['default/image-upload']),
            'plugins' => [
                'imagemanager',
            ]
        ]
    ])->label(Module::t('site', 'CONTENT') . '<span class="glyphicon glyphicon-info-sign tooltip-info" data-toggle="tooltip" data-placement="right" title="Выводится после подзаголовка. Можно использовать HTML тэги"></span>') ?>

    <?php if ($model->sub): ?>

    <p class="bg-info" style="padding: 15px;">Ссылка на эту страницу: <b><?= '/' . substr($model->page->id, 0, strpos($model->page->id, '_') - 1) . '/' . $model->sub->id ?></b></p>
    <hr>
    
    <?= $this->render(\app\modules\site\models\SiteModel::$menu[substr($model->page->id, 0, strpos($model->page->id, '_'))]['form'], ['model' => $model->sub, 'form' => $form]) ?>
    
    <?php endif; ?>
    
    <div class="form-group">
        <?= Html::submitButton(Module::t('site', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
