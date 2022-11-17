<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Fashion</title>

  <meta name="description" content="Fashion - интернет-магазин">
  <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

  <meta name="theme-color" content="#393939">

  <link rel="preload" href="/img/intro/coats-2018.jpg" as="image">
  <link rel="preload" href="/fonts/opensans-400-normal.woff2" as="font">
  <link rel="preload" href="/fonts/roboto-400-normal.woff2" as="font">
  <link rel="preload" href="/fonts/roboto-700-normal.woff2" as="font">

  <link rel="icon" href="/img/favicon.png">
  <link rel="stylesheet" href="/css/style.min.css">

  <script  src="/js/lib/jQuery v3.6.0.js"></script>
  <link rel="stylesheet" type="text/css" href="/js/lib/jQueryUI1.13.2/jquery-ui.min.css">
  <script type="text/javascript" src="/js/lib/jQueryUI1.13.2/jquery-ui.min.js"></script>
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/scripts.js" defer=""></script>
</head>
<body <?=($_SERVER['REQUEST_URI'] == '/') ? 'data-page="catalogDB"' : ''?>>
<header class="page-header">
  <a class="page-header__logo" href="/">
    <img src="/img/logo.svg" alt="Fashion">
  </a>
  <?php if ($_SERVER['REQUEST_URI'] !== '/routs/auth/index.php') { ?>
  
  <nav class="page-header__menu">
    <ul class="main-menu main-menu--header">
      <?php $menu = ($_SERVER['REQUEST_URI'] == '/'|| $_SERVER['REQUEST_URI'] == '/routs/delivery/index.php') ? getMenu() : get_admin_menu ();
      foreach ($menu as $key => $value) { ?>
      <li>
        <a class="main-menu__item <?=($value['name'] == 'Войти') ? 'active' : ''?>" href="<?=$value['puth']?>" data-id="<?=$key+1?>"><?=$value['name']?></a>
      </li>
     <?php } ?>
    </ul>
  </nav>
<?php } ?>
</header>