//var baseUrl = 'http://admin.pricekzp.ru';
var baseUrl = 'http://pricekzp';

//Списки
var types = ['crops', 'warehouses', 'regions'];

/*
 * Добавляем обработчики на элементы страницы при первом открытии
 */
function ActivatePage()
{
    var i = types.length;
    var selectElement;
    //Нажатия на списки Регионы/Склады/Продукция
    while (i--) {
        $('#' + types[i] + '-list').off('click').on('click', 'a', function(e) {
            //Убираем стандартное поведение при клике на ссылку
            e.preventDefault();
            var type = $(this).closest('div.data-content').attr('id');
            var id = $(this).parent().data('id');
            NavLinkAction(type, id);
        });
        //Add event listener
        selectElement = $('select#' + types[i] + '-mobile.cs-select.cs-skin-rotate');
        new SelectFx(selectElement[0], {
            onChange: function(el) {
                SelectChangeAction($(el));
            }
        });
    }
    //Modify top menu in mobile view
    selectElement = $('select.cs-skin-underline');
    new SelectFx(selectElement[0], { newTab : false });
    //"Липкий" заголовок таблиц
    $('#prices-table').stickyTableHeaders({fixedOffset: $('nav.navbar')});
    $('#managers-table').stickyTableHeaders({fixedOffset: $('nav.navbar')});
    //Выделение строки в таблице
    $('#prices-table tr').on('click', function() {
        $('#prices-table tr.hovered').removeClass('hovered')
        $(this).addClass('hovered');
    });
    $('#managers-table tr').on('click', function() {
        $('#managers-table tr.hovered').removeClass('hovered')
        $(this).addClass('hovered');
    });
    //Модальные окна при нажатия на ссылки Спецификация/Цену
    $('a.modal-button').on('click', function() {
        $('#modal-content').empty();
        var textBeforeLink = $(this).prev().text().replace('(', '<br>(');
        if (textBeforeLink !== '') {
            $('#modal-content').append('<h3>' + textBeforeLink + '</h3><hr>' + dataToHtml($(this).data('modal')));
        } else {
            $('#modal-content').append(dataToHtml($(this).data('modal')));
        }
    });
}

/*
 * Обработчик события нажатия на ссылку внутри списка nav
 */
function NavLinkAction(type, id)
{
    //Если нажали не на текущий активный элемент
    if (id !== $('#' + type + '-list ul li.active').data('id')) {
        //Добавляем класс active на нажатый элемент и убираем с предыдущего активного
        $('#' + type + '-list ul li.active').removeClass('active');
        $('#' + type + '-list ul li[data-id=' + id + ']').addClass('active');
        //Удаляем содержимое таблиц
        $('#prices-table').empty();
        $('#managers-table').empty();
        $('#managers').hide();
        //Показываем индикатор загрузки
        $('#main-spinner').show();
        //Получаем текущие id со всех nav
        region = $('#regions-list ul li.active').data('id');
        warehouse = $('#warehouses-list ul li.active').data('id');
        crop = $('#crops-list ul li.active').data('id');
        //Для региона нужно обновить список складов
        if (type === "regions"){
            //Очищаем список складов
            warehouse = 0;
            $('#warehouses-list').empty();
            $('#warehouses-mobile-label').next().remove();
            //Показываем загрузку
            $('#warehouses-spinner').show();
            //Сбрасываем указатель активной культуры на "Все" (элемент с data-id="0")
            $('#crops-list ul li.active').removeClass('active');
            $('#crops-list ul li[data-id=0]').addClass('active');
            crop = 0;
            $('#crops-list').empty();
            $('#crops-mobile-label').next().remove();
            GetCrops(crop);
            //Получаем новый список складов
            GetWarehouses(0, 'list', $('#regions-list ul li.active').data('id'));
        }
        //Обновляем цены
        GetPrices(warehouse, crop, region);
    }
}

function SelectChangeAction(element)
{
    //Удаляем содержимое таблиц
    $('#prices-table').empty();
    $('#managers-table').empty();
    $('#managers').hide();
    //Показываем индикатор загрузки
    $('#main-spinner').show();
    //Получаем текущие id со всех select
    warehouse = $('#warehouses-mobile option:selected').val();
    crop = $('#crops-mobile option:selected').val();
    region = $('#regions-mobile option:selected').val();
    //Для региона нужно обновить список складов и культур
    if (element.attr('id') === 'regions-mobile'){
        warehouse = 0;
        $('#warehouses-list').empty();
        $('#warehouses-mobile-label').next().remove();
        GetWarehouses(warehouse, 'list', region);
        crop = 0;
        $('#crops-list').empty();
        $('#crops-mobile-label').next().remove();
        GetCrops(crop);
    }
    GetPrices(warehouse, crop, region);
}

/**
 * Generate nav html
 * @param {Object} data
 * @param {string} name of list
 * @param {integer} id
 */
function GenerateList(data, name, id, region) {
    
    var dataInfo;
    
    if (data.data && data.data.length) {
        dataInfo = data.data;
    } else {
        dataInfo = data;
    }
    
    if (name === 'warehouses' && region === 0) {
        $('nav#regions-list').empty();
        GenerateList(data.region, "regions", id, 0)
    }
    
    var result = 'Нет данных';
    var resultMobile = 'Нет данных';
    
    if (data.error === '' || name === "regions") {
        result = '<ul>';
        result += '<li data-id="0"><a href="#">Все</a></li>';
        
        resultMobile = (name === 'warehouses' ? '<select id="warehouses-mobile" class="level200 cs-select cs-skin-rotate">' : (name === 'regions' ? '<select id="regions-mobile" class="level300 cs-select cs-skin-rotate">' : '<select id="crops-mobile" class="level100 cs-select cs-skin-rotate">'))
        resultMobile += '<option value="0">' + (name === 'warehouses' ? "Все склады" : (name === 'regions' ? "Все регионы" : "Вся продукция")) + '</option>';
        
        for (var i = 0; i < dataInfo.length; i++) {
            result += '<li data-id="' + dataInfo[i].id + '"><a href="#">' + dataInfo[i].title + '</a></li>';
            resultMobile += '<option value="' + dataInfo[i].id + '">' + dataInfo[i].title + '</option>';
        }
        result += '</ul>';
        resultMobile += '</select>';
    }
    
    //Make active item with data-id == id
    $('nav#' + name + '-list').append(result);
    $('#' + name + '-mobile-label').after(resultMobile);
    $('nav#' + name + '-list ul li[data-id=' + id + ']').addClass('active');
    //Hide spinner and show list
    $('div#' + name + '-spinner').hide();
    $('div#' + name).show();
    //Add event listener
    var selectElement = $('select#' + name + '-mobile.cs-select.cs-skin-rotate');
    new SelectFx(selectElement[0], {
            onChange: function(el) {
                SelectChangeAction($(el));
            }
        });

    $('nav#' + name + '-list').off('click').on('click', 'a', function(e) {
        //Убираем стандартное поведение при клике на ссылку
            e.preventDefault();
            var type = $(this).closest('div.data-content').attr('id');
            var id = $(this).parent().data('id');
            NavLinkAction(type, id);
        });
}

/**
 * Generate prices table html
 * @param {Object} data
 */
function GeneratePriceTable(data) {
    
    var result = '<h2 style="margin-left: -40px;">Нет данных</h2>';
    
    if (data.error === '') {
        
        var headerColumnType;
        var headerColumn = [];
        var rowsColumn = [];
        
        //Determinate table orientation
        //Пока уберу
        /*if (data.warehouses.length > data.products.length) {
            headerColumnType = 'products';
            headerColumn = data.products;
            rowsColumn = data.warehouses;
        } else {*/
            headerColumnType = 'warehouse';
            headerColumn = data.warehouses;
            rowsColumn = data.products;
        //}

        //Header
        var header = '<thead><tr class="">';
        header += '<th class="first-col"></th>';

        for (var i = 0; i < headerColumn.length; i++) {
            header += '<th><p>' + headerColumn[i].title + '</p>';
            if (headerColumnType === 'products') {
                header += '<a href="#modal" class="modal-button" data-modal="' + 'spec;' + headerColumn[i].specification + '">' + 'Спецификация</a>';
            } else {
                header += '<a href="warehouses#wh' + headerColumn[i].id + '">' + 'ТТН</a></th>';
            }
        }
        header += '<th class="last"></th></tr></thead>';
        
        //Body
        var body = '<tbody>';

        //Make table array
        //Add data to table
        var dataArray = [];

        for (var i = 0; i < rowsColumn.length/* * 2; i += 2*/; i++) {
            dataArray[i] = [];
            dataArray[i + 1] = [];
            dataArray[i][0] = '<tr><td class="first-col"><p>' + rowsColumn[i].title;
            if (headerColumnType === 'warehouse') {
                dataArray[i][0] += '</p><a href="#modal" class="modal-button" data-modal="' + 'spec;' + rowsColumn[i].specification + '">' + 'Спецификация</a>';
            } else {
                dataArray[i][0] += '</p><a href="warehouses#wh' + rowsColumn[i].id + '">' + 'ТТН</a>';
            }
            dataArray[i][0] += '</td>';
            for (var j = 1; j <= headerColumn.length + 1; j++) {
                if (j === headerColumn.length + 1) {
                    dataArray[i][j] = '</tr>';
                } else {
                    dataArray[i][j] = '<td>';
                    
                    if (headerColumnType === 'warehouse') {
                        var manager = typeof data.data[j - 1][i] === 'object' ? managerToString(data.data[j - 1][i].manager) : '';
                        var price1 = typeof data.data[j - 1][i] === 'object' ? '<a href="#modal" class="modal-button" data-modal="' + manager + '">' + data.data[j - 1][i].price_no_tax + '</a>' : '';
                    } else {
                        var manager = typeof data.data[i][j - 1] === 'object' ? managerToString(data.data[i][j - 1].manager) : '';
                        var price1 = typeof data.data[i][j - 1] === 'object' ? '<a href="#modal" class="modal-button" data-modal="' + manager + '">' + data.data[i][j - 1].price_no_tax + '</a>' : '';
                    }
                    dataArray[i][j] += price1 + '</td>';
                }
            }
        }

        for (var i = 0; i < rowsColumn.length; i++) {
            body += dataArray[i].join('');
        }
        body += '</tbody>';
        result = header + body;
    }
    
    $('table#prices-table').append(result);
    
    $('#last-update').html('(Обновлено: ' + data.change + ')').show();
    
    $('table#prices-table').stickyTableHeaders('destroy');
    $('table#prices-table').stickyTableHeaders({fixedOffset: $('nav.navbar')});
    
    $('table#prices-table tr').on('click', function() {
        $('table#prices-table tr.hovered').removeClass('hovered')
        $(this).addClass('hovered');
    });
    
    /*$('a.modal-button').on('click', function() {
        $('#modal-content').empty();
        $('#modal-content').append(dataToHtml($(this).data('modal')));
    });*/
    //Модальные окна при нажатия на ссылки Спецификация/Цену
    $('a.modal-button').on('click', function() {
        $('#modal-content').empty();
        var textBeforeLink = $(this).prev().text().replace('(', '<br>(');
        if (textBeforeLink !== '') {
            $('#modal-content').append('<h3>' + textBeforeLink + '</h3><hr>' + dataToHtml($(this).data('modal')));
        } else {
            $('#modal-content').append(dataToHtml($(this).data('modal')));
        }
    });
    
    $('div.spinner').hide();
    $('div#prices').show();
    $('div#managers').show();
}

function managerToString(managerArray) {
    var result = '';
    var data = [];
    
    data[0] = 'manager';
    
    for (var i = 0; i < managerArray.length; i++) {
            data[i + 1] = managerArray[i].name + ';' + managerArray[i].phone + ';' + managerArray[i].email;
        }
    
    result = data.join(';');
    
    return result;
}

function dataToHtml(dataString) {
    var data = dataString.split(';');
    var result = '';
    
    if (data[0] === 'manager') {
        for (var i = 1; i < data.length; i += 3) {
            result += '<h3>' + data[i] + '</h3>';
            result += '<p>Тел: <a href="tel:' + data[i + 1] + '">' + data[i + 1] + '</a></p>';
            result += '<p>Почта: <a href="mailto:' + data[i + 2] + '">' + data[i + 2] + '</a></p>';
            if (data.length > (i + 3)) {
                result += '<hr>';
            }
        }
    }
    else if (data[0] === 'spec') {
        for (var i = 1; i < data.length; i++) {
            result += '<p>' + data[i] + '</p>';
        }
    }
    
    return result;
}

/**
 * Generate managers table html
 * @param {Object} data
 */
function GenerateManagersTable(data) {
    var result = '';
    
    if (data.error === '') {
        //Data
        var headerColumn = ['Склад', 'Продукция', 'Специалист'];
        var rowsColumn = data.managers;

        //Header
        var header = '<thead><tr>';

        for (var i = 0; i < headerColumn.length; i++) {
            if (i === headerColumn.length - 1) {
                header += '<th width="50%"><p>' + headerColumn[i] + '</p></th>';
            } else {
                header += '<th width="25%"><p>' + headerColumn[i] + '</p></th>';
            }
        }
        header += '</tr></thead>';
        
        //Body
        var body = '<tbody>';

        //Make table
        var dataArray = [];
        
        for (var i = 0; i < rowsColumn.length; i++) {
            dataArray[i] = [];
            dataArray[i][0] = '<tr><td>' + rowsColumn[i].warehouses + '</td>';
            dataArray[i][1] = '<td>' + rowsColumn[i].products + '</td>';
            dataArray[i][2] = '<td><div class="manager-cell">' + rowsColumn[i].name + '</div>';
            dataArray[i][3] = '<div class="manager-cell"><a href="tel:' + rowsColumn[i].phone + '">' + rowsColumn[i].phone + '</a></div>';
            dataArray[i][4] = '<div class="manager-cell"><a href="mailto:' + rowsColumn[i].email + '">' + rowsColumn[i].email + '</a></div></td></tr>';
        }

        for (var i = 0; i < rowsColumn.length; i++) {
            body += dataArray[i].join('');
        }
        body += '</tbody>';
        result = header + body;
    }
    
    $('#managers-table').append(result);
    
    $('#managers-table').stickyTableHeaders('destroy');
    $('#managers-table').stickyTableHeaders({fixedOffset: $('nav.navbar')});
    
    $('#managers-table tr').on('click', function() {
        $('#managers-table tr.hovered').removeClass('hovered')
        $(this).addClass('hovered');
    });
    
    if (result === '') {
        $('#managers h2').hide();
    } else {
        $('#managers h2').show();
    }
}

/**
 * Generate managers table html
 * @param {Object} data
 */
function GenerateWarehousesTable(data) {
    
    $('div#organizations').empty();
    
    var result = '';
    
    if (data.error === '') {
        for (var i = 0; i < data.data.length; i++) {
            result += '<h3 id="wh' + data.data[i].id + '">' + data.data[i].title + '</h3>';
            result += '<div class="org-items">';
            for (var j = 0; j < data.data[i].organizations.length; j++) {
                result += '<a href="' + data.data[i].organizations[j].file + '"><div class="org-item position-left"><div class="icon"></div><span>Образец ТТН</span><span>' + data.data[i].organizations[j].title + '</span></div></a>';
            }
            result += '</div><hr>';
        }
    }
    
    $('div#organizations').append(result);
    
    $('div#main-spinner').hide();
    
    var hash = window.location.hash;
    if (hash > '') {
        var top = $('h3'+hash).offset().top - 140;
        $('body,html').animate({scrollTop: top}, 1000);
    }
}

/**
 * AJAX request to REST
 * Get Warehouses Object
 * @param {integer} id
 */
function GetWarehouses(id, type, region) {

    $.get(baseUrl + "/api/v1/pricelist/warehouses", { "warehouseId" : id, "regionId" : region })
        .done(function (data) { if (type === 'list') { GenerateList(data, "warehouses", id, region) } else { GenerateWarehousesTable(data)} });
}

/**
 * AJAX request to REST
 * Get Crops Object
 * @param {integer} id
 */
function GetCrops(id) {

    $.get(baseUrl + "/api/v1/pricelist/crops", { "warehouseId": id })
        .done(function (data) { GenerateList(data, "crops", id, 0) });
}

/**
 * AJAX request to REST
 * Get Price Object
 * @param {integer} warehouseId
 * @param {integer} cropId
 */
function GetPrices(warehouseId, cropId, regionId) {

    $.get(baseUrl + "/api/v1/pricelist/prices", { "warehouseId": warehouseId, "cropId": cropId, "regionId" : regionId })
        .done(function (data) { GeneratePriceTable(data); GenerateManagersTable(data); });
}