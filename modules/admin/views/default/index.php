<?php
 
use yii\helpers\Html;
use app\modules\admin\Module;
 
/* @var $this yii\web\View */
/* @var $model \app\modules\user\models\User */
 
$this->title = Module::t('admin', 'ADMIN_TITLE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-default-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
 
    <div class="text-center">
        <div class="btn-group" role="group">
            <?= Html::a('<span class="glyphicon glyphicon-user"></span><br>' . Module::t('admin', 'LINK_ADMIN_USERS'), ['user/default/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span><br>' . Module::t('admin', 'LINK_ADMIN_GROUPS'), ['group/default/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-home"></span><br>' . Module::t('admin', 'LINK_ADMIN_WAREHOUSES'), ['warehouse/default/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-leaf"></span><br>' . Module::t('admin', 'LINK_ADMIN_CROPS'), ['crop/default/index'], ['class' => 'btn btn-lg btn-default']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-gift"></span><br>' . Module::t('admin', 'LINK_ADMIN_PRODUCTS'), ['product/default/index'], ['class' => 'btn btn-lg btn-default']) ?>
        </div>
    </div>
    <br><br><br>
    <p>
        <?= Html::a(Module::t('admin', 'LINK_ADMIN_ROLES'), ['user/roles/index'], ['class' => 'btn btn-primary']) ?>
    </p>
    
</div>
