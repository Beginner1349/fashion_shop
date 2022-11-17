<?php
// Функции для фильтрации введенных пользовательских данных
function filterName($field) {
    // Санитизация имени пользователя
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    
    // Валидация имени пользователя
    if(filter_var($field, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        return $field;
    } else{
        return FALSE;
    }
}    
function filterEmail($field){
    // Санитизация e-mail
    $field = filter_var(trim($field), FILTER_SANITIZE_EMAIL);
    
    // Валидация e-mail
    if(filter_var($field, FILTER_VALIDATE_EMAIL)){
        return $field;
    } else{
        return FALSE;
    }
}
function filterString($field){
    // Санитизация строки
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if(!empty($field)){
        return $field;
    } else{
        return FALSE;
    }
}
 
// // Определяем переменные и инициализирем с пустыми значениями
// $nameErr = $emailErr = $messageErr = "";
// $name = $email = $subject = $message = "";
 
// // Обрабатываем данные формы при отправке формы
// if($_SERVER["REQUEST_METHOD"] == "POST"){
 
//     // Валидация имени пользователя
//     if(empty($_POST["name"])){
//         $nameErr = "Пожалуйста, введите ваше имя.";
//     } else{
//         $name = filterName($_POST["name"]);
//         if($name == FALSE){
//             $nameErr = "Пожалуйста, введите верное имя.";
//         }
//     }
    
//     // Валидация e-mail
//     if(empty($_POST["email"])){
//         $emailErr = "Пожалуйста, введите адрес вашей электронной почты.";     
//     } else{
//         $email = filterEmail($_POST["email"]);
//         if($email == FALSE){
//             $emailErr = "Пожалуйста, введите действительный адрес электронной почты.";
//         }
//     }
    
//     // Валидация темы сообщения
//     if(empty($_POST["subject"])){
//         $subject = "";
//     } else{
//         $subject = filterString($_POST["subject"]);
//     }
    
//     // Валидация комментария пользователя
//     if(empty($_POST["message"])){
//         $messageErr = "Пожалуйста, введите свой комментарий.";     
//     } else{
//         $message = filterString($_POST["message"]);
//         if($message == FALSE){
//             $messageErr = "Пожалуйста, введите правильный комментарий.";
//         }
//     }
    
//     // Проверяем ошибки ввода перед отправкой электронной почты
//     if(empty($nameErr) && empty($emailErr) && empty($messageErr)){
//         // Электронный адрес получателя
//         $to = 'webmaster@example.com';
        
//         // Создаем заголовки письма
//         $headers = 'From: '. $email . "\r\n" .
//         'Reply-To: '. $email . "\r\n" .
//         'X-Mailer: PHP/' . phpversion();
        
//         // Отправляем электронную почту
//         if(mail($to, $subject, $message, $headers)){
//             echo '<p class="success">Ваше сообщение было отправлено успешно!</p>';
//         } else{
//             echo '<p class="error">Невозможно отправить электронное письмо. Пожалуйста, попробуйте еще раз!</p>';
//         }
//     }
// }