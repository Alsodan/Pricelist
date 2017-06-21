<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use app\modules\admin\rbac\Rbac;

?>

<?php $this->beginContent('@app/views/layouts/layout.php'); ?>

    <?php
    $brandImage = Html::img('/images/logo_only_8.png', ['alt' => Yii::$app->id, 'width' => "40", 'title' => "На главную", 'style' => 'display: table-cell;']);
    $title = Html::tag('div', 'Прайс', ['class' => 'time', 'style' => 'display: table-cell; padding-left: 10px;  padding-top: 4px; vertical-align: top;']);
    NavBar::begin([
        'brandLabel' => Html::tag('div', $brandImage . $title, ['style' => 'display: table;']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => array_filter([
            ['label' => Yii::t('app', 'NAV_HOME'), 'url' => ['/main/default/index']],
            //['label' => Yii::t('app', 'NAV_CONTACT'), 'url' => ['/main/contact/index']],
            Yii::$app->user->can(Rbac::PERMISSION_PRICE_EDIT) ?
                ['label' => Yii::t('app', 'NAV_PRICELIST'), 'url' => ['/product/pricelist/index']] :
                false,
            Yii::$app->user->can(Rbac::PERMISSION_GROUP_EDIT) && !empty(Yii::$app->user->identity->groups) ?
                ['label' => Yii::t('app', 'NAV_MANAGE'), 'url' => ['/group/default/manage', 'id' => Yii::$app->user->identity->groups[0]->id], 'active' => $this->context->module->id == 'group'] :
                false,
            Yii::$app->user->isGuest ?
                ['label' => Yii::t('app', 'NAV_SIGNUP'), 'url' => ['/user/default/signup']] :
                false,
            Yii::$app->user->isGuest ? (
                ['label' => Yii::t('app', 'NAV_LOGIN'), 'url' => ['/user/default/login']]
            ) : false,
            Yii::$app->user->can(Rbac::PERMISSION_ADMINISTRATION) ?
            ['label' => Yii::t('app', 'NAV_ADMIN'), 'items' => [
                ['label' => Yii::t('app', 'NAV_ADMIN_PANEL'), 'url' => ['/admin/default/index']],
                ['label' => Yii::t('app', 'NAV_ADMIN_USERS'), 'url' => ['/admin/user/default/index']],
                ['label' => Yii::t('app', 'NAV_ADMIN_GROUPS'), 'url' => ['/admin/group/default/index']],
                ['label' => Yii::t('app', 'NAV_ADMIN_WAREHOUSES'), 'url' => ['/admin/warehouse/default/index']],
                ['label' => Yii::t('app', 'NAV_ADMIN_CROPS'), 'url' => ['/admin/crop/default/index']],
                ['label' => Yii::t('app', 'NAV_ADMIN_PRODUCTS'), 'url' => ['/admin/product/default/index']],
            ]] :
            false,
            !Yii::$app->user->isGuest ?
                ['label' => Yii::t('app', 'NAV_PROFILE'), 'items' => [
                    ['label' => Yii::t('app', 'NAV_PROFILE'), 'url' => ['/user/profile/index']],
                    ['label' => Yii::t('app', 'NAV_LOGOUT') . ' (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/user/default/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ]
                ]
            ] :
            false,
        ]),
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>

<?php $this->endContent(); ?>
