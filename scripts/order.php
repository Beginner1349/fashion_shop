<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/functions/basic.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/functions/validation.php');

// Подготавливаем данные
function getData() {
  
  return [
        'id_prod' => intval($_POST['id_products']),
        'summa' => intval($_POST['prod_price']),
        'surname' => trim($_POST['surname']),
        'name' => trim($_POST['name']),
        'thirdName' => trim($_POST['thirdName']),
        'phone' => trim($_POST['phone']),
        'email' => trim($_POST['email']),
        'delivery_type' => ($_POST['delivery'] == 'dev-yes') ? 'Курьер' : 'Самовывоз',
        'pay' => ($_POST['pay'] == 'card') ? 'Безналичная' : 'Наличные',
        'city' => isset($_POST['city']) ? $_POST['city'] : 'NULL',
        'street' => isset($_POST['street']) ? $_POST['street'] : 'NULL',
        'home' => isset($_POST['home']) ? $_POST['home'] : 'NULL',
        'aprt' => isset($_POST['aprt']) ? $_POST['aprt'] : 'NULL',
        'comment' => isset($_POST['comment']) ? $_POST['comment'] : '',
    ];
}
// Добавление заказа
function addOrder($data, $pdo) {
    
    global $pdo;
   //Отправляем запрос на добавление данных в БД
    $query = $pdo->prepare("INSERT INTO `orders` (id, product_id, surname, name, thirdName, phone, email, delivery, comment, status, pay, city, street, home, aprt, summ, dates) VALUES (:id, :product_id, :surname,:name, :thirdName, :phone, :email, :delivery, :comment, :status, :pay, :city, :street, :home, :aprt, :summ, :dates)");
    $id = NULL;
    $status = 'Не выполнено!';
    $dates = time();
    
    $query->bindParam(':id', $id);
    $query->bindParam(':product_id', $data['id_prod'], PDO::PARAM_INT);
    $query->bindParam(':surname', $data['surname'], PDO::PARAM_STR);
    $query->bindParam(':name', $data['name'], PDO::PARAM_STR);
    $query->bindParam(':thirdName', $data['thirdName'], PDO::PARAM_STR);
    $query->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
    $query->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $query->bindParam(':delivery', $data['delivery_type'], PDO::PARAM_STR);
    $query->bindParam(':comment', $data['comment'], PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':pay', $data['pay'], PDO::PARAM_STR);
    $query->bindParam(':city', $data['city'], PDO::PARAM_STR);
    $query->bindParam(':street', $data['street'], PDO::PARAM_STR);
    $query->bindParam(':home', $data['home'], PDO::PARAM_STR);
    $query->bindParam(':aprt', $data['aprt'], PDO::PARAM_STR);
    $query->bindParam(':summ', $data['summa'], PDO::PARAM_INT);
    $query->bindParam(':dates', $dates, PDO::PARAM_INT);

    $query->execute();
    $order_id = $pdo->lastInsertId();
    $info = $query->errorInfo();
    return  $info;
}


try {
    //Connection to data base
    global $pdo;

    // Получаем данные из массива POST
    $data = getData();
   
    // Добавляем запись в таблицу Заказы
     $order = addOrder($data, $pdo);
    
    // Возвращаем клиенту успешный ответ если добавлена запись
    if ($order == ["00000",null,null]) {
        echo json_encode([
        'code' => 'success',
        'data' => $data,
       ]); 
    } else {
         echo json_encode([
        'code' => 'error',
        'data' => "Ошибка SQL запроса",
    ]);
    }
   
}
catch (Exception $e) {
    // Возвращаем клиенту ответ с ошибкой
    echo json_encode([
        'code' => 'error',
        'message' => $e->getMessage()
    ]);
}