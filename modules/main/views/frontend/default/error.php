<?php

use app\modules\main\Module;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'Ошибка!';
?>
<!-- Menu -->
<?php echo \Yii::$app->user->isGuest ? $page->generateMenu() : ''; ?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3><?= nl2br(Html::encode($message)) ?></h3>

    <p><?= Module::t('main', 'ERROR_MESSAGE_GLOBAL') ?></p>

</div>
