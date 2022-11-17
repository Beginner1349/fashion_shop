<?php
session_start();
unset($_SESSION['auth']);
setcookie("login", '', time() - 60 * 60 * 24 * 30, "/");
header('Location: /');
