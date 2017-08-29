<?php

use yii\helpers\Html;
use app\modules\group\Module;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $group app\modules\group\models\Group */

$this->title = Module::t('group', 'GROUP_MANAGMENT') . ': ' . $group->title;
$this->params['breadcrumbs'][] = $this->title;

$ajaxUrl = Yii::$app->urlManager->createUrl(['/group/default/user-change', 'id' => $group->id]);

?>
<div class="group-manage">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b><?= Module::t('group', 'GROUP') ?>: <?= $group->title ?></b></h3>
            </div>
            <div class="panel-body text-center">
                <div class="btn-group" role="group">
                    <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span><br>' . Module::t('group', 'GROUP_INFO'), ['manage', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span><br>' . Module::t('group', 'GROUP_UPDATE'), ['update', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('group', 'GROUP_WAREHOUSES_MANAGE'), ['warehouses', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-leaf"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_MANAGE'), ['group-products', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('group', 'PRODUCTS_MANAGE'), ['products', 'id' => $group->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('group', 'GROUP_USERS_MANAGE'), ['users', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-tag"></span><br>' . Module::t('group', 'GROUP_PRODUCTS_USERS_MANAGE'), ['products-users', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width']) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-sunglasses"></span><br>' . Module::t('group', 'GROUP_LOG'), ['log', 'id' => $group->id], ['class' => 'btn btn-primary btn-medium-width active']) ?>
                </div>
                <hr>
                <pre style="text-align: left;">
                    <?php //var_dump($dataProvider->query->all()); ?>
                </pre>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'attribute' => 'date',
                                    'value' => function($data) {return Yii::$app->formatter->asDatetime($data->date, 'medium');},
                                    'label' => \app\modules\main\Module::t('main', 'DATETIME'),
                                ],
                                [
                                    'attribute' => 'title',
                                    'label' => \app\modules\main\Module::t('main', 'TITLE'),
                                ],
                                'user.profile.name',
                                'user.email',
                                [
                                    'attribute' => 'changelog.field_text',
                                    'value' => function($data) {
                                        $result = [];
                                        $className = $data->object_type;
                                        $dataModel = $className::findOne($data->object_id);
                                        //$dict = (new $data->object_type())->attributeLabels();
                                        foreach ($data->changelog as $value) {
                                            //$result[] = $dict[$value->field];
                                            if ($data->object_type == app\modules\product\models\Price::className()) {
                                                $result[] = /*$value->field_text . '<br>' . */$dataModel->warehouse->title . '<br>' . $dataModel->product->fullTitle;
                                            }
                                        }
                                        return implode('<hr>', $result);
                                    },
                                    'label' => /*\app\modules\main\Module::t('main', 'FIELD')*/'Цена',
                                    'format' => 'html',
                                    'contentOptions' => [
                                        'style' => 'min-width: 220px; max-width: 220px; font-size: 12px;',
                                        'width' => '220px',
                                    ]
                                ],
                                [
                                    'attribute' => 'changelog.old_value',
                                    'value' => function($data) {
                                        $result = [];
                                        foreach ($data->changelog as $value) {
                                            if ($data->object_type == app\modules\product\models\Price::className()) {
                                                $result[] = app\modules\product\models\Price::getPriceText($value->old_value, $value->field == 'price_status');
                                            }
                                        }
                                        return '<br>' . implode('<hr>', $result);
                                    },
                                    'label' => \app\modules\main\Module::t('main', 'OLD_VALUE'),
                                    'format' => 'html',
                                    'contentOptions' => [
                                        'style' => 'min-width: 140px; max-width: 140px; font-size: 12px;',
                                        'width' => '140px',
                                    ]
                                ],
                                [
                                    'attribute' => 'changelog.new_value',
                                    'value' => function($data) {
                                        $result = [];
                                        foreach ($data->changelog as $value) {
                                            if ($data->object_type == app\modules\product\models\Price::className()) {
                                                $result[] = app\modules\product\models\Price::getPriceText($value->new_value, $value->field == 'price_status');
                                            }
                                        }
                                        return '<br>' . implode('<hr>', $result);
                                    },
                                    'label' => \app\modules\main\Module::t('main', 'NEW_VALUE'),
                                    'format' => 'html',
                                    'contentOptions' => [
                                        'style' => 'min-width: 140px; max-width: 140px; font-size: 12px;',
                                        'width' => '140px',
                                    ]
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>
