<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

?>

<?php $this->beginContent('@app/views/layouts/layout.php'); ?>

    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => array_filter([
            ['label' => Yii::t('app', 'NAV_HOME'), 'url' => ['/main/default/index']],
            ['label' => Yii::t('app', 'NAV_CONTACT'), 'url' => ['/main/contact/index']],
            Yii::$app->user->isGuest ?
                ['label' => Yii::t('app', 'NAV_SIGNUP'), 'url' => ['/user/default/signup']] :
                false,
            Yii::$app->user->isGuest ? (
                ['label' => Yii::t('app', 'NAV_LOGIN'), 'url' => ['/user/default/login']]
            ) : false,
            !Yii::$app->user->isGuest ?
            ['label' => Yii::t('app', 'NAV_ADMIN'), 'items' => [
                ['label' => Yii::t('app', 'NAV_ADMIN_PANEL'), 'url' => ['/admin/default/index']],
                ['label' => Yii::t('app', 'NAV_ADMIN_USERS'), 'url' => ['/admin/user/default/index']],
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
