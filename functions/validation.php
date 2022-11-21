<?php
// Функции для фильтрации введенных пользовательских данных
function filterName($field) {
    // Санитизация имени пользователя
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    
    // Валидация имени пользователя
    if(filter_var($field, FILTER_VALIDATE_REGEXP, ["options"=> ["regexp"=>"/^[a-zA-Z\s]+$/"]])){
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