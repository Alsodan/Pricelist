<?php

namespace app\modules\site\models;

use app\modules\site\models\Page;
use yii\helpers\ArrayHelper;
use app\components\widgets\LinkedItemsWidget;

class SiteModel extends \yii\base\Model
{
    //Хранилище данных и связей
    //level - Уровень высоты (CSS z-index) для мобильных выпадающих списков
    //all - Наименование пункта для выбора всех
    private $data = [
        'price' => ['data' => null, 'addon' => []],
        'warehouse' => ['data' => null, 'addon' => ['price'], 'level' => '200', 'all' => 'Все склады'],
        'product' => ['data' => null, 'addon' => ['price']],
        'crop' => ['data' => null, 'addon' => ['product'], 'level' => '100', 'all' => 'Вся продукция'],
        'region' => ['data' => null, 'addon' => ['warehouse'], 'level' => '300', 'all' => 'Все регионы'],
        'user' => ['data' => null, 'addon' => ['price', 'warehouse', 'product']],
    ];
    //Пространство имен для классов моделей
    private $dataModelsNamespace = 'app\\api\\modules\\v1\\models\\';
    //Текущая страница
    private $page;
    //Подменю страницы
    private $submenu;
    //Данные для страницы
    private $sub;
    //Cookie
    private $cookies = [
        'region' => 0,
        'warehouse' => 0,
        'crop' => 0,
    ];
    //Состав меню
    public static $menu = [
        'pricelist' => ['link' => '/', 'title' => 'Прайслист'],
        'warehouses' => ['link' => '/warehouses', 'title' => 'Склады', 'sub' => '\\app\\api\\modules\\v1\\models\\Organization', 'form' => 'organizationForm'],
        'supplier' => ['link' => '/supplier', 'title' => 'Поставщику'],
        'products' => ['link' => '/products', 'title' => 'Продукция', 'sub' => '\\app\\api\\modules\\v1\\models\\Crop', 'form' => 'cropForm'],
        'contacts' => ['link' => '/contacts', 'title' => 'Контакты'],
    ];
    
    //Конструктор
    public function __construct($page = 'pricelist', $sub = 0) {
        parent::__construct();
        //Текущая страница
        $this->page = Page::findPage($page, $sub);
        //Данные для страницы
        $subModelName = null;
        if (isset(static::$menu[$page]['sub'])) {
            $subModelName = static::$menu[$page]['sub'];
            $this->submenu = ArrayHelper::map($subModelName::find()->all(), 'id', 'title');

            $this->sub = $subModelName::findOne($sub);
        }
        //Cookie
        $this->setCookies();
    }

    //Проверка наличия кук и установка их значениями по-умолчанию
    private function setCookies()
    {
        //Куки нам нужны только на странице прайса
        if ($this->page->id === 'pricelist_0') {
            //Запоминаем куки
            $this->cookies = $this->filterCookie($this->getUnsecuredCookieFromSite());
            //Пишем на сайт
            $this->setUnsecuredCookieToSite();
        }
    }
    
    //Установка наших кук на сайт.
    //Куки невалидируемые, чтобы скрипты на клиенте могли их менять
    private function setUnsecuredCookieToSite()
    {
        foreach ($this->cookies as $key => $value) {
            setcookie($key, $value);
        }
    }
    
    //Выбор нужных нам кук
    private function filterCookie($siteCookies)
    {
        $cookies = [];
        foreach ($this->cookies as $key => $value) {
            if ($siteCookies->has($key)) {
                $cookies[$key] = $siteCookies->get($key)->value;
            }
        }
        
        return $cookies;
    }
    
    //Чтение невалидных кук с сайта
    private function getUnsecuredCookieFromSite()
    {
        // получение коллекции (yii\web\CookieCollection) из компонента "response"
        //Т.к. куки ставятся скриптом на клиенте, при получении отключаем валидацию кук
        \Yii::$app->request->enableCookieValidation=false;
        $siteCookies = \Yii::$app->request->cookies;
        \Yii::$app->request->enableCookieValidation=true;
        
        return $siteCookies;
    }
    
    //Геттеры для приватных членов класса
    public function getPage()
    {
        return $this->page;
    }

    public function getSubmenu()
    {
        return $this->submenu;
    }
    
    public function getSub()
    {
        return $this->sub;
    }
    
    public function getCookie($name)
    {
        return isset($this->cookies[$name]) ? $this->cookies[$name] : 0;
    }

    /**
     * Возвращает данные по имени
     * @param string $name Имя массива
     * @return array|[]
     */
    public function getDataByName($name)
    {
        $innerName = $this->prepareName($name);
        if (!$this->checkName($innerName)) {
            //Если нет такого массива данных, возвращаем пустой массив
            return [];
        } else {
            //Возвращаем данные
            return $this->dataByName($innerName);
        }
    }

    /**
     * Подготавливает имя для работы в классе
     * @param string $name
     * @return string
     */
    private function prepareName($name)
    {
        return strtolower($name);
    }

    /**
     * Проверка существования массива данных с таким именем
     * @param string $innerName Имя
     * @return bool
     */
    private function checkName($innerName)
    {
        return array_key_exists($innerName, $this->data);
    }

    /**
     * Возвращает массив данных по имени
     * @param string $innerName Имя массива данных
     * @return array|[]
     */
    private function dataByName($innerName)
    {
        //Если есть связанные данные, то сначала получаем их
        if (!empty($this->data[$innerName]['addon'])) {
            foreach ($this->data[$innerName]['addon'] as $value) {
                $this->dataByName($value);
            }
        }
        //Если данные еще не загружены, то загружаем
        if (is_null($this->data[$innerName]['data'])) {
                $this->loadData($innerName);
        }
        //Возвращаем загруженные данные
        return $this->data[$innerName]['data'];
    }

    /**
     * Обновление данных в модели. Классы данных должны реализовывать интерфейс app\interfaces\SiteInterface
     * @param string $innerName
     */
    private function loadData($innerName)
    {
        $className = $this->getClassName($innerName);
        $addon = ['cookie' => $this->cookies];
        //Подготавливаем связанные данные для получения основных данных
        foreach ($this->data[$innerName]['addon'] as $value) {
            $name = $value . 's';
            $data = $this->data[$value]['data'];
            $addon = array_merge($addon, [$name => $data]);
        }
        //Обновляем данные
        $this->data[$innerName]['data'] = $className::getBaseData($addon);
    }

    /**
     * Возвращает полное имя класса из его имени
     * @param string $innerName
     * @return string
     */
    private function getClassName($innerName)
    {
        $className = $this->dataModelsNamespace . ucfirst($innerName);
        return $className::className();
    }

    /*
     * Виджет для вывода пунктов меню
     */
    public function menuWidget($isMain = false, $id = 'default', $defaultFirstItem = 'Основная страница')
    {
        $links = [];
        //Добавляем верхний элемент
        if ($defaultFirstItem) {
            $links[] = $defaultFirstItem;
        }
        
        //Главное меню или подменю
        $menuData = $isMain ? static::$menu : $this->submenu;
        foreach ($menuData as $key => $value) {
            $links[$key] = isset($value['title']) ? $value['title'] : $value;
        }

        $correctPageId = strpos($this->page->id, '_') ? substr($this->page->id, 0, strpos($this->page->id, '_')) : $this->page->id;
        return LinkedItemsWidget::widget([
            'links' => $links,
            'options' => ['id' => $id],
            'selectedKey' => $isMain ? $correctPageId : (isset($this->sub['id']) ? $this->sub['id'] : 0),
            'linkRoute' => $isMain ? ['index', 'page' => 'key', 'sub' => 0] : ['index', 'page' => $correctPageId, 'sub' => 'key'],
        ]);
    }

    /**
     * Возвращает HTML разметку меню с активным пунктом
     * @param string $activeItem Активный пункт меню (роут)
     * @return string
     */
    public function generateMenu($activeItem = '')
    {
        //Если не указан активный пункт меню, то активным пробуем сделать пункт с путем текущей страницы
        if (empty($activeItem)) {
            $activeItem = strpos($this->page->id, '_') ? substr($this->page->id, 0, strpos($this->page->id, '_')) : $this->page->id;
        }
        
        $menuHtml = '<nav class="navbar"><div class="container">';
        //Логотип
        $menuHtml .= '<a href="/"><div class="navbar-header position-left"></div></a>';
        //Телефон в шапке
        $menuHtml .= '<div class="navbar-item position-right last"><a href="tel:' . \Yii::$app->params['sitePhone'] . '">' . \Yii::$app->params['sitePhone'] . '</a></div>';
        //Почта в шапке
        $menuHtml .= '<div class="navbar-item position-right"><a href="mailto:' . \Yii::$app->params['siteEmail'] . '">' . \Yii::$app->params['siteEmail'] . '</a></div>';
        //Мобильное меню
        $menuHtml .= '<div class="top-menu mobile-show position-center"><section><select class="cs-select cs-skin-underline"><option value="0" disabled>Меню</option>';
        foreach (static::$menu as $key => $menuItem) {
            $menuHtml .= '<option data-link="' . $menuItem['link'] . '"' . ($key === $activeItem ? ' selected' : '') . '>' . $menuItem['title'] . '</option>';
        }
        $menuHtml .= '</select></section></div>';
        //Десктопное меню
        $menuHtml .= '<div class="top-menu desktop-show position-center">';
        foreach (static::$menu as $key => $menuItem) {
            $menuHtml .= '<div class="navbar-item' . ($key === $activeItem ? ' active' : ' ' . $menuItem['title']) . '"><a href="' . $menuItem['link'] . '">' . $menuItem['title'] . '</a></div>';
        }
        $menuHtml .= '</div>';
        $menuHtml .= '</div></nav>';

        return $menuHtml;
    }

    /**
     * Возвращает HTML разметку массива данных
     * @param string $name
     * @return string HTML
     */
    public function generateNav($name, $active)
    {
        $data = $this->getDataByName($name);
        
        if ($name === 'warehouse') $data = \app\api\modules\v1\models\Warehouse::findWarehousesWithParams(0, 0, $this->cookies['region']);
        
        $navHtml = '<nav id="' . $name . 's-list" class="list position-center"><ul><li data-id="0"' . ($active == 0 ? ' class="active"' : '') . '><a href="#">Все</a></li>';
        foreach ($data as $value) {
            $navHtml .= '<li data-id="' . $value->id . '"' . ($active == $value->id ? ' class="active"' : '') . '><a href="#">' . $value->title . '</a></li>';
        }
        
        $navHtml .= '</ul></nav>';
        return $navHtml;
    }
    
    /**
     * Возвращает HTML разметку массива данных для отображения на мобильных
     * @param string $name
     * @return string HTML
     */
    public function generateMobileNav($name, $active)
    {
        $data = $this->getDataByName($name);
        
        if ($name === 'warehouse') $data = \app\api\modules\v1\models\Warehouse::findWarehousesWithParams(0, 0, $this->cookies['region']);
        
        $navHtml = '<select id="' . $name . 's-mobile" class="level' . $this->data[$name]['level'] . ' cs-select cs-skin-rotate">';
        $navHtml .= '<option ' . ($active == 0 ? 'selected' : '') . ' value="0">' . $this->data[$name]['all'] . '</option>';
        foreach ($data as $value) {
            $navHtml .= '<li data-id="' . $value->id . '"><a href="#">' . $value->title . '</a></li>';
            $navHtml .= '<option ' . ($active == $value->id ? 'selected' : '') . ' value="' . $value->id . '">' . $value->title . '</option>';
        }
        
        $navHtml .= '</select>';

        return $navHtml;
    }
    
    /**
     * Возвращает скомпонованную и отформатированную дату последнего изменения прайса
     * @return string
     */
    public function generateLastChange($onlyData = false)
    {
        $lastchange = \app\components\behaviors\models\Changes::getLastChangeDate();
        return $onlyData ? $lastchange : '(Обновлено: ' . $lastchange . ')';
    }
    
    /**
     * Возвращает HTML код таблицы цен
     * @return string
     */
    public function generatePriceTable()
    {
        //TODO - длинновато, но пока оставлю для читабельности. Отрефакторить
        //Данные для таблицы
        $prices = $this->getDataByName('price');
        $warehouses = $this->getDataByName('warehouse');
        $products = $this->getDataByName('product');
        
        //Шапка должна быть меньше или равна количеству строк
        //Определяем что будет в шапке: продукция или склады
        //Пока уберу
        $headerColumnType = /*count($warehouses) > count($products) ? 'product' : */'warehouse';
        $headerColumn = /*count($warehouses) > count($products) ? $products : */$warehouses;
        $rowColumnType = /*count($warehouses) > count($products) ? 'warehouse' : */'product';
        $rowColumn = /*count($warehouses) > count($products) ? $warehouses : */$products;
        
        //Если нет данных, то так и выводим
        if (empty($warehouses) || empty($products) || empty($prices)) {
            return '<h2 style="margin-left: -40px;">Нет данных</h2>';
        }

        //Создаем пустой массив с нужными индексами
        //Индексы - это ИД складов и продукции
        $tableData = [];
        foreach ($rowColumn as $row) {
            //Первая колонка для названий
            $tableData[0][$row->id] = '';
            foreach ($headerColumn as $header) {
                $tableData[$header->id][$row->id] = '';
            }
        }
        
        //Заполняем массив ценами
        $headerAttributeName = $headerColumnType . '_id';
        $rowAttributeName = $rowColumnType . '_id';
        foreach ($prices as $price) {
            $tableData[$price->$headerAttributeName][$price->$rowAttributeName] = $price;
            //Для каждой цены собираем массив с данными о менеджерах
            $price->manager = $price->fillManagers();
        }
        
        //Создаем HTML разметку таблицы
        //Заголовок таблицы. Первая ячейка - пустая
        $tableHeader = '<thead><tr><th class="first-col"></th>';
        foreach ($headerColumn as $headerCell) {
            $tableHeader .= '<th><p>' . $headerCell->title;
            if ($headerColumnType === 'product') {
                $tableHeader .= ($headerCell->subtitle ? ('<br>(' . $headerCell->subtitle . ')') : '')  . '</p><a href="#modal" class="modal-button" data-modal="spec;' . $headerCell->specification . '">Спецификация</a>';
            } else {
                $tableHeader .= '</p><a href="/warehouses#wh' . $headerCell->id . '">ТТН</a>';
            }
            $tableHeader .= '</th>';
        }
        $tableHeader .= '<th class="last"></th></tr></thead>';
        //Тело таблицы
        $tableBody = '<tbody>';
        foreach ($rowColumn as $row) {
            //Первая колонка для названий
            $tableBody .= '<tr><td class="first-col"><p>' . $row->title;
            if ($headerColumnType === 'warehouse') {
                $tableBody .= ($row->subtitle ? ('<br>(' . $row->subtitle . ')') : '')  . '</p><a href="#modal" class="modal-button" data-modal="spec;' . $row->specification . '">Спецификация</a>';
            } else {
                $tableBody .= '</p><a href="warehouses#wh' . $row->id . '">ТТН</a>';
            }
            $tableBody .= '</td>';
            foreach ($headerColumn as $header) {
                $tableBody .= '<td>';
                if (is_object($tableData[$header->id][$row->id])) {
                    $tableBody .= '<a href="#modal" class="modal-button" data-modal="' . $this->managerToString($tableData[$header->id][$row->id]->manager) . '">' . $tableData[$header->id][$row->id]->getPrice('no_tax') . '</a>';
                }
                $tableBody .= '</td>';
            }
            $tableBody .= '</tr>';
        }
        $tableBody .= '</tbody>';

        return $tableHeader . $tableBody;
    }
    
    /**
     * Возвращает HTML код таблицы цен для вывода в PDF
     * @return string
     */
    public function generatePdfPriceTable($region, $warehouse, $crop)
    {
        //TODO - длинновато, но пока оставлю для читабельности. Отрефакторить
        //Данные для таблицы
        /*$prices = $this->getDataByName('price');
        $warehouses = $this->getDataByName('warehouse');
        $products = $this->getDataByName('product');*/
        
        $warehouses = \app\api\modules\v1\models\Warehouse::findWarehousesWithParams($warehouse, $crop, $region);
        $products = \app\api\modules\v1\models\Product::findProductsWithParams($warehouses, $crop);
        $prices = \app\api\modules\v1\models\Price::findPricesWithParams($warehouses, $products);
        
        //Шапка должна быть меньше или равна количеству строк
        //Определяем что будет в шапке: продукция или склады
        //Пока уберу
        $headerColumnType = /*count($warehouses) > count($products) ? 'product' : */'warehouse';
        $headerColumn = /*count($warehouses) > count($products) ? $products : */$warehouses;
        $rowColumnType = /*count($warehouses) > count($products) ? 'warehouse' : */'product';
        $rowColumn = /*count($warehouses) > count($products) ? $warehouses : */$products;
        
        //Если нет данных, то так и выводим
        if (empty($warehouses) || empty($products) || empty($prices)) {
            return '<h2 style="text-align: center;">Нет данных</h2>';
        }

        //Создаем пустой массив с нужными индексами
        //Индексы - это ИД складов и продукции
        $tableData = [];
        foreach ($rowColumn as $row) {
            //Первая колонка для названий
            $tableData[0][$row->id] = '';
            foreach ($headerColumn as $header) {
                $tableData[$header->id][$row->id] = '';
            }
        }

        //Заполняем массив ценами
        $headerAttributeName = $headerColumnType . '_id';
        $rowAttributeName = $rowColumnType . '_id';
        foreach ($prices as $price) {
            if ($price->price_status < (\app\api\modules\v1\models\Price::NONEED_NO_TAX)) {
                $tableData[$price->$headerAttributeName][$price->$rowAttributeName] = $price;
            }
        }
        
        //Убираем склады, в которых сейчас не закупают товар
        $prodCount = count($products);
        foreach ($headerColumn as $header) {
            $emptyCount = 0;
            foreach ($rowColumn as $row) {
                if (empty($tableData[$header->id][$row->id])) {
                    $emptyCount++;
                }
            }
            if ($emptyCount == $prodCount) {
                unset($tableData[$header->id]);
                unset($headerColumn[$header->id]);
            }
        }

        //Создаем HTML разметку таблицы
        //Заголовок таблицы. Первая ячейка - пустая
        $tableHeader = '<thead><tr><th style="width: 100px; line-stacking-strategy: block-line-height; padding: 2px 5px;"></th>';
        foreach ($headerColumn as $headerCell) {
            $tableHeader .= '<th style="width: 100px; line-stacking-strategy: block-line-height; padding: 2px 5px; text-align: center;">' . $headerCell->title . ($headerColumnType === 'product' ? ($headerCell->subtitle ? '<br>(' . $headerCell->subtitle . ')' : '') : '');
            $tableHeader .= '</th>';
        }
        $tableHeader .= '</tr></thead>';
        //Тело таблицы
        $tableBody = '<tbody>';
        foreach ($rowColumn as $row) {
            //Первая колонка для названий
            $tableBody .= '<tr><td style="width: 100px; line-stacking-strategy: block-line-height; padding: 2px 5px;">' . $row->title . ($rowColumnType === 'product' ? ($row->subtitle ? '<br>(' . $row->subtitle . ')' : '') : '');
            $tableBody .= '</td>';
            foreach ($headerColumn as $header) {
                $tableBody .= '<td style="width: 100px; line-stacking-strategy: block-line-height; padding: 2px 5px;">';
                if (is_object($tableData[$header->id][$row->id])) {
                    $tableBody .= $tableData[$header->id][$row->id]->getPrice('no_tax');
                }
                $tableBody .= '</td>';
            }
            $tableBody .= '</tr>';
        }
        $tableBody .= '</tbody>';

        return $tableHeader . $tableBody;
    }
    
    /**
     * Преобразует массив данных о менеджерах в строчку подсказки
     * @param array $managerArray Массив данных о менеджерах
     * @return string
     */
    private function managerToString($managerArray) {
        $result = 'manager;';
        
        foreach ($managerArray as $manager) {
            $result .= $manager['name'] . ';' . $manager['phone'] . ';' . $manager['email'] . ';';
        }

        return mb_substr($result, 0, mb_strlen($result) - 1);
    }

    /**
     * Возвращает HTML код таблицы менеджеров
     * @return string
     */
    public function generateManagersTable()
    {
        $managers = $this->getDataByName('user');
    
        //Если нет данных, то так и выводим
        if (empty($managers)) {
            return '<h2 style="margin-left: -40px;">Нет данных</h2>';
        }
        
        //Шапка
        $headerColumns = ['Склад' => 'width="24%"', 'Продукция' => 'width="24%"', 'Специалист' => 'width="48%"'];
        $tableHeader = '<thead><tr>';
        foreach ($headerColumns as $key => $value) {
            $tableHeader .= '<th ' . $value . '><p>' . $key . '</p></th>';
        }
        $tableHeader .= '</tr></thead>';
        //Тело таблицы
        $tableBody = '<tbody>';
        foreach ($managers as $manager) {
            //Собираем данные о складах и продукции этого менеджера
            $manager->checkWarehousesAndProducts(\yii\helpers\ArrayHelper::getColumn($this->data['warehouse']['data'], 'id'), \yii\helpers\ArrayHelper::getColumn($this->data['product']['data'], 'id'));
            $tableBody .= '<tr><td>' . $manager->warehouses . '</td>';
            $tableBody .= '<td>' . $manager->products . '</td>';
            $tableBody .= '<td><div class="manager-cell">' . $manager->profileName . '</div>';
            $tableBody .= '<div class="manager-cell"><a href="tel:' . $manager->profilePhone . '">' . $manager->profilePhone . '</a></div>';
            $tableBody .= '<div class="manager-cell"><a href="mailto:' . $manager->profileEmail . '">' . $manager->profileEmail . '</a></div></td></tr>';
        }

        $tableBody .= '</tbody>';
        
        return $tableHeader . $tableBody;
    }
    
    /*
     * Возвращает HTML код списка органиазций
     * @return string
     */
    public function generateOrganizationList()
    {
        $warehouses = $this->getDataByName('warehouse');
        
        $dataHtml = '';
        
        foreach ($warehouses as $warehouse) {
            $dataHtml .= '<h3 id="wh' . $warehouse->id . '">' . $warehouse->title . '</h3>';
            $dataHtml .= '<div class="org-items">';
            foreach ($warehouse->activeOrganizations as $org) {
                $dataHtml .= '<div class="org position-left"><div class="org-item">';
                //Иконка
                $dataHtml .= '<a href="' . $org->file . '" target="_blank"><div class="icon"></div></a>';
                //Подпись к иконке
                $dataHtml .= '<a href="' . $org->file . '" target="_blank"><span>Образец ТТН</span></a></div>';
                //Название
                $dataHtml .= '<div class="org-link"><a href="warehouse/' . $org->id . '"><span>' . $org->title . '</span>';
                //Ссылка "Подробнее"
                $dataHtml .= '<div class="link"><span>Подробнее</span></div></a>';
                $dataHtml .= '</div></div>';
            }
            $dataHtml .= '</div><hr>';
        }

        return $dataHtml;
    }
}
