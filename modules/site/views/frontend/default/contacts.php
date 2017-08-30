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
<!-- Contacts -->
    <div class="contacts-index position-center">
        <h1><?= $site->page->header ?></h1>
        <?= $site->page->subheader ?>
    </div>
    <?= $site->page->content ?>
</div>
    <!-- Managers -->
    <div id="managers" class="container-fluid">
        <h2 class="position-center">Связаться с нами и получить консультацию Вы можете обратившись напрямую к специалистам:</h2>
        <table id="managers-table" class="table-hover">
            <?= $site->generateManagersTable(); ?>
        </table>
    </div>
    <div style="display: block; clear: both;">
    <!-- Map -->
        <script src="https://api-maps.yandex.ru/2.1/?load=package.full&lang=ru_RU&onload=init" type="text/javascript"></script>
        <script type="text/javascript">
            var kzpOrgMap;
            function init (ymaps) {
                kzpOrgMap = new ymaps.Map("map", {
                    center: [45.04552400001937, 38.992985999999966],
                    zoom: 17,
                    controls: ['zoomControl', 'fullscreenControl']
                });
                    
                placemark = new ymaps.Placemark(kzpOrgMap.getCenter(), {
                    hintContent: 'ООО "КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО"',
                    balloonContent: 'ООО "КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО"<br>Россия, 350042, Краснодарский край,   г. Краснодар, ул. Колхозная, 5'
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
                    
                kzpOrgMap.geoObjects.add(placemark);
            }
        </script>
        <h3>Карта проезда</h3>
        <div id="map" style="width: 100%; height: 500px;"></div>
        <!-- /Map -->
    </div>

