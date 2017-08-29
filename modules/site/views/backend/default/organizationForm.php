<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\site\Module;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use vova07\imperavi\Widget;

?>
    <h3>Данные об организации</h3>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                    
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                    
    <?= $form->field($model, 'latitude')->textInput() ?>
                    
    <?= $form->field($model, 'longitude')->textInput() ?>
                    
    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>


