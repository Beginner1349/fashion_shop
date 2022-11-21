<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
//Задаем условие выполнения скрипта - нажата кнопка "Войти"
if (isset($_POST['login'])) {
    //Инициализируем пустой массив для сообщений об ошибках
    $errors = [];
    //Проверяем заполнены ли поля
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        //Экранируем и записываем данные из формы авторизации в переменные
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);
        //Делаем запрос к БД где есть такая же пара логин/пароль
        $querySignin = $pdo->prepare("SELECT * FROM `users` WHERE `login` = :login AND `password` = :password LIMIT 1");
        $querySignin->bindParam(':login', $login);
        $querySignin->bindParam(':password', $password);
        $querySignin->execute();
        $resultCheck = $querySignin->fetch(PDO::FETCH_ASSOC);
        //Проверяем, соответствует ли введенная пара логин/пароль той что лежит в БД
         if ($resultCheck['login'] === $login && $resultCheck['password'] === $password) {
             //Если такая пара логин/пароль нашлась - стартуем сессию
             session_start(); 
            //Авторизуем пользователя
            $_SESSION['auth'] = true;
           
            //Сохраняем в сессию необходимые данные 
            $_SESSION['user'] = [
                "id" => $resultCheck['id'],
                "full_name" => $resultCheck['full_name'],
                "login" => $resultCheck['login'],
                "role" => $resultCheck['role_name'],
            ];
            setcookie("login", $_SESSION['user']['login'], time() + 60 * 60 * 24 * 30, "/");
            echo json_encode([
                "code" => "success",
                "role" => $_SESSION['user']['role'],
            ], JSON_UNESCAPED_UNICODE);
            exit();
   
        } else {
             $errors = 'Вы ввели неверные данные, попробуйте ещё раз';
             echo json_encode(["error" => $errors], JSON_UNESCAPED_UNICODE);
             // exit();
        }
    } else {
        $errors = 'Заполните пустые поля, пожалуйста';
        echo json_encode(["error" => $errors], JSON_UNESCAPED_UNICODE);
        // exit();
    }
}