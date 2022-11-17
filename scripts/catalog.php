<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Получение данных из массива _GET
function getOptions() {
    // Категория, цены и дополнительные данные
    $categoryId = (isset($_GET['category'])) ? intval($_GET['category']) : 0;
    $selectMenuCategory = (isset($_GET['selectMenuCategory'])) ? intval($_GET['selectMenuCategory']) : 0;
    $minPrice = (isset($_GET['min_price'])) ? intval($_GET['min_price']) : 0;
    $maxPrice = (isset($_GET['max_price'])) ? intval($_GET['max_price']) : 120000;
    $page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
    $limit = (isset($_GET['limit'])) ? intval($_GET['limit']) : 6;
    $new = ($_GET['new'] == 'on') ? 'new' : '';
    $sale = ($_GET['sale'] == 'on') ? 'sale' : '';

    // Сортировка...
    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'price';
    $sort_order = (isset($_GET['sort_order'])) ? $_GET['sort_order'] : 'asc';
    
    return [
        'category_id' => $categoryId,
        'min_price' => $minPrice,
        'max_price' => $maxPrice,
        'sort' => $sort,
        'sort_order' => $sort_order,
        'new' => $new,
        'sale' => $sale,
        'needs_data' => $needsData,
        'page' => $page,
        'limit' => $limit,
        'selectMenuCategory' => $selectMenuCategory,
    ];
}
//var_dump($limit);
// Получение товаров
function getGoods($options, $pdo) {
   global $pdo;
    // Обязательные параметры
    $minPrice = $options['min_price'];
    $maxPrice = $options['max_price'];
    $sortBy = $options['sort'];
    $sortOrder = $options['sort_order'];

     // Вычисляем номер страницы и параметры для sql limit...
    $page = $options['page'];
    $limit = intval($options['limit']);
    $start = ($page - 1) * $limit;

    // Необязательные параметры
    $categoryId = $options['category_id'];
    $selectMenuCategory = $options['$selectMenuCategory'];
    $newWhere = ($options['$selectMenuCategory'] == 2) ? " p.status='new' and " : '';
    $saleWhere = ($options['$selectMenuCategory'] == 3) ? " p.status='sale' and " : '';
    $categoryWhere = ($categoryId !== 0) ? " p_c.categories_id = $categoryId and " : '';
    $newWhere = ($options['new'] == 'new') ? " p.status='new' and " : '';
    $saleWhere = ($options['sale'] == 'sale') ? " p.status='sale' and " : '';

    $query = "
        select * from
            products as p
        left join
         products_has_categories as p_c
        on 
         p.id=p_c.products_id
        where
         $categoryWhere
         $newWhere
         $saleWhere
         (p.price between $minPrice and $maxPrice)
    ";
    //Выполняем выборку по заданным параметрам
    $data = $pdo->prepare($query);
    $data->execute();
    $rows = $data->fetchAll(PDO::FETCH_ASSOC);
    //Вычисляем кол-во строк затронутых последним запросом
    $countAll = $data->rowCount();
    //Добавляем в запрос атрибут limit для пагинации
     $queryTotal = $query . "
            order by $sortBy $sortOrder
            limit $start, $limit
        ";
    //Снова делаем выборку товаров только с параметрами для пагинации
    $data = $pdo->prepare($queryTotal);
    $data->execute();
    $goods = $data->fetchAll(PDO::FETCH_ASSOC);
    return [
        'countAll' => $countAll,
        'goods' => $goods
    ];
}

try {
    // Подключаемся к базе данных
    global $pdo;

    // Получаем данные от клиента
    $options = getOptions();

    // Получаем товары
    $data = getGoods($options, $pdo);

    // Возвращаем клиенту успешный ответ
    echo json_encode([
        'code' => 'success',
        'data' => $data
    ]);
}
catch (Exception $e) {
    // Возвращаем клиенту ответ с ошибкой
    echo json_encode([
        'code' => 'error',
        'message' => $e->getMessage()
    ]);
}