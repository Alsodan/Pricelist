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
 
    <p>
        <?= Html::a(Module::t('admin', 'LINK_ADMIN_USERS'), ['user/default/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('admin', 'LINK_ADMIN_GROUPS'), ['main/group/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('admin', 'LINK_ADMIN_ROLES'), ['user/roles/index'], ['class' => 'btn btn-primary']) ?>
    </p>
    
    <p>
        <?= Html::a(Module::t('admin', 'LINK_ADMIN_USERS'), ['user/default/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('admin', 'LINK_ADMIN_ROLES'), ['user/roles/index'], ['class' => 'btn btn-success']) ?>
    </p>
    
</div>
