'use strict';

// Модуль каталога для работы с БД...
var catalogDB = (function($) {

    var ui = {
        $form: $('#filters-form'),
        $prices: $('#prices'),
        $pricesLabel: $('#prices-label'),
        $minPrice: $('#min-price'),
        $maxPrice: $('#max-price'),
        $categoryBtn: $('.js-category'),
        $sort: $('#sort'),
        $sort_order: $('#sort_order'),
        $goods: $('#goods'),
        $goodsTemplate: $('#goods-template'),
        $checkbox: $('#filter_checkbox'),//чекбоксы new & sale
        $count_all: $('#count-all'),//span общего количества товаров на странице
        $limit: $('#count_items'), //select количество товаров на странице
        $pag: $('#pagination'), //вывод нумерации страниц
        $menuCat: $('.main-menu__item') //категории фильтрации в меню new & sale
    };
    
    var selectedCategory = 0,
        selectMenuCategory = 0,
        goodsTemplate = _.template(ui.$goodsTemplate.html()),
        pagTemplate = _.template($('#pagination-template').html());
    var selectedSort = 'price',
        selectedSortBy = '';
    
    // Инициализация модуля
    function init() {
        _initPrices({
            minPrice: 350,
            maxPrice: 120000
        });
         _bindHandlers();
        _getData({resetPage: true});
    }

 // Смена лимита
    function _changeLimit() {
        _getData({resetPage: true});
    }

    // Смена страницы
    function _changePage(e) {
        e.preventDefault();
        e.stopPropagation();

        var $page = $(e.target).closest('li');
        ui.$pag.find('li').removeClass('active');
        $page.addClass('active');

        _getData();
    }
    
    // Навешиваем события
    function _bindHandlers() {
        ui.$categoryBtn.on('click', _changeCategory);
        ui.$sort.on('change', _getSort);
        ui.$sort_order.on('change', _getSortBy);
        ui.$limit.on('change', _changeLimit);
        ui.$pag.on('click', 'a', _changePage);
        ui.$checkbox.on('change', 'input', _getData);
        ui.$menuCat.on('click', 'a', _getFilterCategory);
    }
    //Получаем данные сортировки
    function _getSort() {
        selectedSort = ui.$sort.val();
      _getData();  
    }
 function _getSortBy() {
        selectedSortBy = ui.$sort_order.val();
      _getData();  
    }
    //Получаем данные сортировки из меню
    function _getFilterCategory() {
        var $id = $(this);
        ui.$menuCat.removeClass('active');
        $id.addClass('active');
        selectMenuCategory = $id.attr('data-id');
       _getData(); 
    }
    // Сброс фильтров
    function _resetFilters() {
        ui.$minPrice.val(350);
        ui.$maxPrice.val(120000);
        ui.$checkbox.find('input').removeAttr('checked');
    }

    // Смена категории
    function _changeCategory() {
        var $this = $(this);
        ui.$categoryBtn.removeClass('active');
        $this.addClass('active');
        selectedCategory = $this.attr('data-id');
        _resetFilters();
        _getData({resetPage: true});
    }

    // Изменение диапазона цен, реакция на событие слайдера
    function _onSlidePrices(event, elem) {
        _updatePricesUI({
            minPrice: elem.values[0],
            maxPrice: elem.values[1]
        });
    }

    // Обновление цен
    function _updatePricesUI(options) {
        ui.$pricesLabel.html(options.minPrice + ' - ' + options.maxPrice + ' руб.');
        ui.$minPrice.val(options.minPrice);
        ui.$maxPrice.val(options.maxPrice);
    }

    // Инициализация цен с помощью jqueryUI
    function _initPrices(options) {
        ui.$prices.slider({
            range: true,
            min: options.minPrice,
            max: options.maxPrice,
            values: [options.minPrice, options.maxPrice],
            slide: _onSlidePrices,
            change: _getData
        });
        _updatePricesUI(options);
    }

    // Обновление слайдера с отключением события change
    function _updatePrices(options) {
        ui.$prices.slider({
            change: null
        }).slider({
            min: options.minPrice,
            max: options.maxPrice,
            values: [options.minPrice, options.maxPrice]
        }).slider({
            change: _getData
        });
        _updatePricesUI(options);
    }

 // Рендер пагинации
    function _renderPagination(options) {
        var countAll = options.countAll,
            countItems = options.countItems,
            page = options.page,
            limit = options.limit,
            countPages = Math.ceil(countAll / limit),
            start = (page - 1) * limit + 1,
            end = start + countItems - 1;

    ui.$pag.html(pagTemplate({
            page: page,
            countPages: countPages
        }));
    }

// Получение опций-настроек для товаров
    function _getOptions(resetPage) {
        var page = !resetPage ? + ui.$pag.find('li.active').attr('data-page') : 1,
            limit = + ui.$limit.val();

        return {
            page: page,
            limit: limit
        }
    }

  // Ошибка получения данных
    function _catalogError(response) {
        console.error('response', response);
        console.log(response);
    }

    // Успешное получение данных
    function _catalogSuccess(response) {
        ui.$goods.html(goodsTemplate({goods: response.data.goods}));
        //Вывод количества товаров последнего SQL запроса
        ui.$count_all.text(response.data.countAll);
    
        if (response.data.prices) {
            _updatePrices({
                minPrice: +response.data.prices.min_price,
                maxPrice: +response.data.prices.max_price
            });
        }
    }


    // Получение данных
    function _getData(options) {
        //Собираем в кучу данные для отправки на сервер
         var resetPage = options && options.resetPage,
            options = _getOptions(resetPage);
        var catalogData = 'category=' + selectedCategory + '&' + 'sort=' + selectedSort 
        + '&' + 'sort_order=' + selectedSortBy + '&' + 'limit=' + options.limit + '&' + 'page=' + options.page + '&' + 'selectMenuCategory=' + selectMenuCategory + '&' + ui.$form.serialize();

        if (options && options.needsData) {
            catalogData += '&needs_data=' + options.needsData;
        }
        $.ajax({
            url: 'scripts/catalog.php',
            data: catalogData,
            type: 'GET',
            cache: false,
            dataType: 'json',
            error: _catalogError,
            success: function(response) {
                if (response.code === 'success') {
                    _catalogSuccess(response);
                     _renderPagination({
                        page: options.page,
                        limit: options.limit,
                        countAll: response.data.countAll,
                        countItems: response.data.goods.length
                    });
                } else {
                    _catalogError(response);
                }
            }
        });
    }

    // Экспортируем наружу
    return {
        init: init
    }
    
})(jQuery);