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
    <div class="warehouse-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
    </div>
    <?= $site->page->content ?>
    <div id="organizations" class="container">
        <div style="display: block;">
        <!-- Info -->
        <div style="width: 50%; float: left;">
            <?php if ($model->address): ?>
                <h3>Адрес:</h3>
                <p><?= $model->address ?></p>
            <?php endif; ?>
            <?php if ($model->phone): ?>
                <h3>Контактный телефон:</h3>
                <p><?= $model->phone ?></p>
            <?php endif; ?>
        </div>
        <!-- /Info -->
        <!-- File -->
        <div style="width: 50%; float: left; text-align: center;">
            <?php if ($model->file): ?>
                <h3>Образец ТТН</h3>
                <div class="org-item">
                    <a href="<?= $model->file ?>" target="_blank">
                        <div class="icon"></div>
                    </a>
                    <a href="<?= $model->file ?>" target="_blank">
                        <span>Скачать файл</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <!-- /File -->
        </div>
        <div style="display: block; clear: both;">
            <!-- Info -->
            <?php if ($model->info): ?>
                <h3>Информация:</h3>
                <p><?= $model->info ?></p>
            <?php endif; ?>
            <!-- /Info -->
        </div>
        <div style="display: block; clear: both;">
        <!-- Map -->
        <?php if ($model->latitude && $model->longitude): ?>
            <script src="https://api-maps.yandex.ru/2.1/?load=package.full&lang=ru_RU&onload=init" type="text/javascript"></script>
            <script type="text/javascript">
                var kzpOrgMap;
                function init (ymaps) {
                    kzpOrgMap = new ymaps.Map("map", {
                      center: [<?= $model->latitude ?>, <?= $model->longitude ?>],
                      zoom: 17,
                      controls: ['zoomControl', 'fullscreenControl']
                    });
                    
                    placemark = new ymaps.Placemark(kzpOrgMap.getCenter(), {
                        hintContent: '<?= $model->title ?>',
                        balloonContent: '<?= $model->title ?><br><?= $model->address ?>'
                    }, {
                        // Опции.
                        // Необходимо указать данный тип макета.
                        iconLayout: 'default#image',
                        // Своё изображение иконки метки.
                        iconImageHref: '/images/icon-56x56.png',
                        // Размеры метки.
                        iconImageSize: [56, 56],
                        // Смещение левого верхнего угла иконки относительно
                        // её "ножки" (точки привязки).
                        iconImageOffset: [-28, -64]
                    });
                    
                    kzpOrgMap.geoObjects
                        .add(placemark);
                  }
              </script>
            <h3>Карта проезда</h3>
            <div id="map" style="width: 100%; height: 500px;"></div>
        <?php endif; ?>
        <!-- /Map -->
        </div>
    </div>
</div>

