<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/basic.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
getHeader();
?>
<main class="page-authorization">
  <h1 class="h h--1">Авторизация</h1>
  <form class="custom-form" id="signin_form" method="POST">
    <input type="email" class="custom-form__input" id="log" name="login" required placeholder="Введите логин/e-mail">
    <input type="password" class="custom-form__input" id="pass" name="password" autocomplete="off" required placeholder="Введите пароль">
    <button class="button" type="submit" id="sub" name="signin">Войти в личный кабинет</button>
    <div class="msg" id="signin_info"></div>
  </form>
</main>
<?php
getFooter();
?>