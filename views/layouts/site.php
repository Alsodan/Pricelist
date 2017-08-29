<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\SiteAsset;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

SiteAsset::register($this);

$this->registerJs('
    <!-- Yandex.Metrika counter -->
    (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter45500916 = new Ya.Metrika({ id:45500916, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); 
    <!-- /Yandex.Metrika counter -->
    <!-- Google Analytics -->
    (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","https://www.google-analytics.com/analytics.js","ga");
    ga("create", "UA-80434673-5", "auto");
    ga("send", "pageview");
    <!-- /Google Analytics -->
');

//Метатэги
$this->registerMetaTag([
    'name' => 'description',
    'content' => isset($this->params['description']) ? $this->params['description'] : '',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => isset($this->params['keywords']) ? $this->params['keywords'] : '',
]);
//Заголовок страницы
$this->title = isset($this->params['title']) ? $this->params['title'] : '';

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, minimum-scale=1, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="position-left">© 2017 ПРАЙС ООО «КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО»</p>
        <p class="position-right"></p>
    </div>
</footer>
<!-- Modal -->
<a href="#x" class="overlay" id="modal"></a>
<div class="popup">
    <div id="modal-content"></div>
    <a class="close" title="Закрыть" href="#close"></a>
</div>

<?php $this->endBody() ?>

<noscript>
    <div><img src="https://mc.yandex.ru/watch/45500916" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
</body>
</html>
<?php $this->endPage() ?>
