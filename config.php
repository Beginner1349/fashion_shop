<?php
$host = '127.0.0.1';
$username = 'root';
$password = 'pass';
$db_name = 'inetshop';
try {
    // подключаемся к серверу
    $username = 'root';
   $password = 'pass';
    $dsn = 'mysql:dbname=inetshop;host=127.0.0.1';
   $pdo = new PDO($dsn, $username, $password);
   return $pdo;
    echo "Успешное подключение к БД";
}
catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}

