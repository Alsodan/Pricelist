<?php

/* @var $this yii\web\View */

//Meta
$this->params['description'] = $site->page->meta_description;
$this->params['keywords'] = $site->page->meta_keywords;
$this->params['title'] = $site->page->title;
?>
<!-- Menu -->
<?= $site->generateMenu() ?>

<div class="container">
<!-- Product -->
    <div class="product-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
    </div>
    <?= $site->page->content ?>
</div>

