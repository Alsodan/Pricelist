<?php
 
use app\modules\admin\Module;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
 
/* @var $this \yii\web\View */
/* @var $content string */
 
/** @var \yii\web\Controller $context */
$context = $this->context;
 
if (isset($this->params['breadcrumbs'])) {
    $panelBreadcrumbs = [['label' => Module::t('admin', 'ADMIN_TITLE'), 'url' => ['/admin/default/index']]];
    $breadcrumbs = $this->params['breadcrumbs'];
} else {
    $panelBreadcrumbs = [Module::t('admin', 'ADMIN_TITLE')];
    $breadcrumbs = [];
}
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
    'activateParents' => true,
    'items' => array_filter([
        ['label' => Yii::t('app', 'NAV_ADMIN_PANEL'), 'url' => ['/admin/default/index']],
        
        ['label' => Yii::t('app', 'NAV_ADMIN_USERS'), 'url' => ['/admin/user/default/index'], 'active' => $context->module->id == 'user'],
        ['label' => Yii::t('app', 'NAV_ADMIN_GROUPS'), 'url' => ['/admin/group/default/index'], 'active' => $context->module->id == 'group'],
        ['label' => Yii::t('app', 'NAV_ADMIN_WAREHOUSES'), 'url' => ['/admin/warehouse/default/index'], 'active' => $context->module->id == 'warehouse'],
        ['label' => Yii::t('app', 'NAV_ADMIN_CROPS'), 'url' => ['/admin/crop/default/index'], 'active' => $context->module->id == 'crop'],
        ['label' => Yii::t('app', 'NAV_ADMIN_PRODUCTS'), 'url' => ['/admin/product/default/index'], 'active' => $context->module->id == 'product'],
        
        ['label' => Yii::t('app', 'NAV_LOGOUT'), 'url' => ['/user/default/logout'], 'linkOptions' => ['data-method' => 'post']],
    ]),
]);
NavBar::end();
?>
 
<div class="container">
    <?= Breadcrumbs::widget([
        'links' => ArrayHelper::merge($panelBreadcrumbs, $breadcrumbs),
    ]) ?>
    <?= $content ?>
</div>
 
<?php $this->endContent(); ?>