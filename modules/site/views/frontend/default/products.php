<?php

/* @var $this yii\web\View */
//JS
$this->registerJs('
    <!-- DOM Ready Scripts -->
    $(document).ready(function() {
        //Modify top menu in mobile view
        selectElement = $("select.cs-skin-underline");
        new SelectFx(selectElement[0], { newTab : false });
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
<!-- Products -->
    <div class="products-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
    </div>
    <?= $site->page->content ?>
</div>