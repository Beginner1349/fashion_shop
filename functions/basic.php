<?php

/**
 * Function for get header
 */
function getHeader() 
{
	require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
}
/**
 * Function for get footer 
 */
function getFooter() 
{
	require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');
}


/**
 * Function for get menu
 */
function getMenu ()
{
   global $pdo;
   $sql = $pdo->prepare("SELECT * FROM `menu`");
   $sql->execute();
   $menu = [];
   $menu = $sql->fetchAll(PDO::FETCH_ASSOC);
   return $menu;
}

/**
 * Function for get adminmenu
 */
function get_admin_menu ()
{
   global $pdo;
   $sql = $pdo->prepare("SELECT * FROM `menuadmin`");
   $sql->execute();
   $menu = [];
   $menu = $sql->fetchAll(PDO::FETCH_ASSOC);
   return $menu;
}

 /**
 * Function for a cropping a string
 * @param Cut the menu string to 12 characters and substituted {...}
 * @param string $line cutting
 * @return string
 */
function cutString(string $line, $length = 12, $appends = '...'): string
{
   if (strlen($line) > $length) {
      $line = mb_substr($line, 0, $length) . $appends;
   };
   return $line;
}

//Функция которая возвращает количество товаров в БД

function get_count_products()
{
   global $pdo;
   $members = $pdo->query("SELECT COUNT(*) as count FROM `products`")->fetchColumn();
   $members = intval($members);
   return $members;
}

//Функция возвращает id категории по названию
function get_category_id()
{
   global $pdo;
   $name = $_GET['category'];
   $query = $pdo->prepare("SELECT `id` FROM `categories` WHERE `name` = '$name'");
   $query->execute();
   $category_id = $query->fetchAll(PDO::FETCH_ASSOC);
   $category_id = $category_id[0]['id'];
   return $category_id;
}

//Функция возвращает список категорий товаров
function get_products_category ()
{
   global $pdo;
   $name_groups_query = $pdo->prepare("SELECT `name` FROM `categories`");
   $name_groups_query->execute();
   $group_names = $name_groups_query->fetchAll(PDO::FETCH_COLUMN);
   return $group_names;
}

//Функция возвращает дату доставки товара

function getDateDelivery()
{
   $month = date("F");
   $day1 = date("d", strtotime('+5 day'));
   $day2 = date("d", strtotime('+7 day'));
   $stringDate = $day1 . " " . "-" . " " .  $day2 . " " . $month;
   return $stringDate;
}