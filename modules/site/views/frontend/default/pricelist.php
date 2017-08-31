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
    <div class="prices-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
        <?= $site->page->content ?>
    </div>
    <br>
    <!-- Filters -->
    <div id="main-data">
        <!-- Mobile -->
        <div class="mobile-show position-center">
            <section>
                <label id="regions-mobile-label" class="select-label">Выберите регион:</label>
                <?= $site->generateMobileNav('region', $site->getCookie('region')); ?>
            </section>
            <section>
                <label id="warehouses-mobile-label" class="select-label">Выберите склад:</label>
                <?= $site->generateMobileNav('warehouse', $site->getCookie('warehouse')); ?>
            </section>
            <section>
                <label id="crops-mobile-label" class="select-label">Выберите продукцию:</label>
                <?= $site->generateMobileNav('crop', $site->getCookie('crop')); ?>
            </section>
        </div>
        <!-- Desktop -->
        <div class="desktop-show position-center">
            <div id="regions" class="data-content">
                <h3>Регионы</h3>
                <?= $site->generateNav('region', $site->getCookie('region')); ?>
            </div>
            <div id="warehouses" class="data-content">
                <h3>Склады</h3>
                <div id="warehouses-spinner" class="spinner small"></div>
                <?= $site->generateNav('warehouse', $site->getCookie('warehouse')); ?>
            </div>
            <div id="crops" class="data-content">
                <h3>Продукция</h3>
                <div id="crops-spinner" class="spinner small"></div>
                <?= $site->generateNav('crop', $site->getCookie('crop')); ?>
            </div>
        </div>
    </div>
</div>

<!-- Prices -->
<div id="prices" class="data-content">
    <div class="container with-items">
        <h2 class="position-center">ПРАЙСЛИСТ</h2>
        <div class="pdf">
            <div class="org-item pdf-item">
                <a href="<?= yii\helpers\Url::to(['pricelist-pdf']) ?>" style="position: absolute; top: 25px; right: 45px;" target="_blank"><span style="font-size: 12px;">Скачать в PDF</span></a>
                <a href="<?= yii\helpers\Url::to(['pricelist-pdf']) ?>" target="_blank"><div class="icon-pdf"></div></a></div>
        </div>
    </div>
        <div id="last-update" class="container position-center last-update">
            <?= $site->generateLastChange(); ?>
        </div>
        
    <div class="responsive-table">
        <table id="prices-table" class="table-hover">
            <?= $site->generatePriceTable(); ?>
        </table>
    </div>
</div>
<!-- Managers -->
<div id="managers" class="container-fluid">
    <h2 class="position-center">Связаться с нами и получить консультацию Вы можете обратившись напрямую к специалистам:</h2>
    <div class="responsive-table">
        <table id="managers-table" class="table-hover">
            <?= $site->generateManagersTable(); ?>
        </table>
    </div>
</div>
<div id="main-spinner" class="spinner"></div>

