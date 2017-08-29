<?php

/* @var $this yii\web\View */
//JS
$this->registerJs('
    <!-- DOM Ready Scripts -->
    $(document).ready(function() {
        $(".spinner").hide();
        ActivatePage();
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
<!-- Supplier -->
    <div class="supplier-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
        <?= $site->page->content ?>
    </div>
</div>