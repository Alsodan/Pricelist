<?php

/* @var $this yii\web\View */
//JS
$this->registerJs('
    <!-- DOM Ready Scripts -->
    $(document).ready(function() {
        new SelectFx($("select.cs-skin-underline")[0], { newTab : false });
        var hash = window.location.hash;
        if (hash > "") {
            var top = $("h3" + hash).offset().top - 140;
            $("body, html").animate({scrollTop: top}, 1000);
        }
    });
');
//Meta
$this->params['description'] = $site->page->meta_description;
$this->params['keywords'] = $site->page->meta_keywords;
$this->params['title'] = $site->page->title;
?>
<!-- Menu -->
<?= $site->generateMenu() ?>

<div class="container">
<!-- Warehouses & Elevators -->
    <div class="warehouses-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
    </div>
    <?= $site->page->content ?>
    <div id="organizations" class="container">
        <?= $site->generateOrganizationList(); ?>
    </div>
</div>

