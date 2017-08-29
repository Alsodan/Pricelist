<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\site\Module;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use vova07\imperavi\Widget;

?>
    <h3>Данные о продукции</h3>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


