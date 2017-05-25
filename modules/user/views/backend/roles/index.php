<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\grid\ActionColumn;
use app\modules\user\models\backend\User;
use app\components\grid\SetColumn;
use app\components\grid\LinkColumn;
use kartik\date\DatePicker;
use app\modules\user\Module;
use kartik\sortinput\SortableInput;

use app\components\widgets\LinkedItemsWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('user', 'ADMIN_USERS_ROLES_INDEX_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-info">
        <p class="col-lg-1 col-md-1 col-sm-2">
            <span class="glyphicon glyphicon-info-sign" style="font-size: 48px;"></span>
        </p>
        <p class="col-lg-11 col-md-11 col-sm-10">
            <p>Выберите пользователя и перетащите в колонку "Разрешен доступ" разрешения на редактирование соответствующих товаров из списка "Все доступы".<br>
            Чтобы убрать доступ - перетащите разрешение из списка "Разрешен доступ" обратно в список "Все доступы".</p>
        </p>
    </div>
    <div class="row">
    <div class="col-lg-3 col-md-3">
        <h5 class="text-center"><b>Пользователи</b></h5>
        <?= LinkedItemsWidget::widget([
            'links' => \yii\helpers\ArrayHelper::map(\app\modules\user\models\common\User::find()->select(['id', 'username'])->asArray()->all(), 'id', 'username'),
            'options' => ['id' => 'users'],
            'selectedKey' => 7
        ]) ?>
    </div>
    
    <div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-5">
        <h5 class="text-center"><b>Разрешен доступ</b></h5>
<?= SortableInput::widget([
    'name'=>'kv-conn-1',
    'items' => [
        1 => ['content' => 'Item # 1'],
        2 => ['content' => 'Item # 2'],
        3 => ['content' => 'Item # 3'],
        4 => ['content' => 'Item # 4'],
        5 => ['content' => 'Item # 5'],
    ],
    'hideInput' => false,
    'sortableOptions' => [
        'connected'=>true,
        'itemOptions'=>['class'=>'alert alert-success'],
        'options' => ['style' => 'min-height: 50px'],
    ],
    'options' => ['class'=>'form-control', 'readonly'=>true]
]);?>
</div>
<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-5 col-sm-offset-2">
    <h5 class="text-center"><b>Все доступы</b></h5>
<?= SortableInput::widget([
    'name'=>'kv-conn-2',
    'items' => [
        10 => ['content' => 'Item # 10'],
        20 => ['content' => 'Item # 20'],
        30 => ['content' => 'Item # 30'],
        40 => ['content' => 'Item # 40'],
        50 => ['content' => 'Item # 50'],
    ],
    'hideInput' => false,
    'sortableOptions' => [
        'itemOptions'=>['class'=>'alert alert-info'],
        'connected'=>true,
        'options' => ['style' => 'min-height: 50px'],
    ],
    'options' => ['class'=>'form-control', 'readonly'=>true]
]);?>
</div>
    </div>

</div>
